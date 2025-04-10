<?php
namespace App\UI\Post;

use App\Model\PostFacade;
use App\Model\CategoryFacade;
use App\Model\CommentFacade;

use Nette;
use Nette\Application\UI\Form;
use Nette\Http\FileUpload;
use Nette\Utils\Paginator;

final class PostPresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private PostFacade $postFacade,
        private CategoryFacade $categoryFacade,
        private CommentFacade $commentFacade,
    ) {
    }

    protected function beforeRender(): void
    {
        $this->template->addFilter('nl2br', function ($s) {
            return nl2br($s);
        });
    }

    public function renderDefault(int $page = 1): void
    {
        $this->template->categories = $this->categoryFacade->getCategories();
        // Získání hodnoty z GET parametru
        $postId = $this->getHttpRequest()->getQuery('post');
    
        // Získání všech kategorií pro selectbox
        $this->template->categories = $this->categoryFacade->getCategories();
        $this->template->selectedCategory = $postId;
    
        // Filtrování podle vybrané kategorie (pokud je)
        if ($postId) {
            $this->template->games = $this->postFacade->getPostByCategory($postId);
            $totalCount = $this->postFacade->getPostCountByCategory($postId);
            $posts = $this->postFacade->getPostByCategoryWithLimit($postId, ($page - 1) * 5, 5);
        } else {
            $this->template->games = $this->postFacade->getPostsWithCategoryName();
            $totalCount = $this->postFacade->getTotalPostsCount();
            $posts = $this->postFacade->getPostsWithoutStatusFilter(($page - 1) * 5, 5);
        }
    
        // Stránkování
        $paginator = new Paginator();
        $paginator->setItemsPerPage(5);
        $paginator->setPage($page);
        $paginator->setItemCount($totalCount);
    
        // Předání do šablony
        $this->template->posts = $posts;
        $this->template->paginator = $paginator;
    }
    


    


    

    public function renderEdit(int $id): void
    {
        $post = $this->postFacade->getPostById($id);
        if (!$post) {
            $this->error('Příspěvek nebyl nalezen');
        }

        // Předání kategorií do šablony
        $this->template->categories = $this->categoryFacade->getCategories();
    
        // Předání výchozích hodnot do formuláře
        $this['postForm']->setDefaults([
            'title' => $post->title,
            'content' => $post->content,
            'category_id' => $post->category_id,
        ]);
    
        // Převod $post na pole a přidání category_name
        $postData = $this->getPostData($post);
    
        $this->template->post = $postData;
    }

    public function renderShow(int $id): void
    {
        $post = $this->postFacade->getPostById($id);

        if (!$post) {
            $this->error('Příspěvek nebyl nalezen');
        }

        if ($post->status === 'ARCHIVED' && !$this->getUser()->isLoggedIn()) {
            $this->flashMessage('Tento příspěvek je dostupný pouze přihlášeným uživatelům.', 'error');
            $this->redirect('Home:');
        }

        $this->postFacade->incrementViews($id);

        $comments = [];
        if ($post->status === 'OPEN') {
            $comments = $this->commentFacade->getCommentsByPost($id);
        } elseif ($post->status === 'CLOSED' && $this->getUser()->isLoggedIn()) {
            $comments = $this->commentFacade->getCommentsByPost($id);
        }

        $this->template->post = $this->getPostData($post);
        $this->template->comments = $comments;
    }

    private function getPostData($post): array
    {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'content' => $post->content,
            'image' => $post->image,
            'category_id' => $post->category_id,
            'category_name' => $this->categoryFacade->getCategoryNameById($post->category_id),
            'views_count' => $post->views_count,
            'status' => $post->status
        ];
    }

    protected function createComponentPostForm(): Form
    {
        $form = new Form;
        $form->addText('title', 'Název')->setRequired();
        $form->addTextArea('content', 'Obsah')->setRequired();
    
        $form->addSelect('category_id', 'Kategorie', $this->categoryFacade->getCategoriesName())
            ->setPrompt('Vyber kategorii')
            ->setRequired();
    
        $form->addSelect('status', 'Status', [
            'OPEN' => 'Open',
            'CLOSED' => 'Closed',
            'ARCHIVED' => 'Archived',
        ])
            ->setRequired();
    
        $form->addUpload('image', 'Obrázek')
            ->setRequired(false)
            ->addRule(Form::IMAGE, 'Obrázek musí být ve formátu JPEG, PNG nebo GIF')
            ->addRule(Form::MAX_FILE_SIZE, 'Obrázek nesmí být větší než 2 MB', 2 * 1024 * 1024);
    
        $form->addSubmit('save', 'Uložit')->setHtmlAttribute('class', 'post-button');
    
        $form->onSuccess[] = [$this, 'postFormSucceeded'];
    
        return $form;
    }

    public function postFormSucceeded(Form $form, \stdClass $values): void
    {
        $imagePath = null;
        /** @var FileUpload $image */
        $image = $values->image;
        if ($image->isOk() && $image->isImage()) {
            $imagePath = 'uploads/' . uniqid() . '_' . $image->getSanitizedName();
            $image->move($imagePath);
        }
    
        if ($this->getParameter('id')) {
            // Include status when updating the post
            $this->postFacade->updatePost($this->getParameter('id'), $values->title, $values->content, $values->category_id, $imagePath, $values->status);
            $this->flashMessage('Příspěvek byl upraven.', 'success');
        } else {
            // Include status when creating the post
            $this->postFacade->addPost($values->title, $values->content, $values->category_id, $imagePath, $values->status);
            $this->flashMessage('Příspěvek byl přidán.', 'success');
        }
    
        $this->redirect('default');
    }

    public function handleDelete(int $id): void
    {
        $post = $this->postFacade->getPostById($id);
        
        if ($post->status === 'ARCHIVED') {
            $this->flashMessage('Tento příspěvek nemůže být odstraněn, protože je archivován.', 'error');
            $this->redirect('default');
        }
    
        $this->postFacade->deletePost($id);
        $this->flashMessage('Příspěvek byl odstraněn.', 'success');
        $this->redirect('default');
    }

    public function handleDeleteImage(int $id): void
    {
        $this->postFacade->deleteImage($id);
        $this->flashMessage('Obrázek byl odstraněn.', 'success');
        if ($this->isAjax()) {
            $this->redrawControl('image');
            $this->redrawControl('imagebutton');
        } else {
            $this->redirect('Post:show', $id);
        }
    }

    protected function createComponentCommentForm(): Form
    {
        $form = new Form;
        
        $form->addText('name', 'Jméno')
            ->setRequired('Zadejte své jméno.');
    
        $form->addEmail('email', 'E-mail')
            ->setRequired('Zadejte svůj e-mail.');
    
        $form->addTextArea('content', 'Komentář')
            ->setRequired('Napište svůj komentář.');
    
        $form->addSubmit('save', 'Odeslat');
    
        $form->onSuccess[] = [$this, 'commentFormSucceeded'];
        return $form;
    }

    public function commentFormSucceeded(Form $form, \stdClass $values): void
    {
        $postId = $this->getParameter('id');
        if (!$postId) {
            $this->flashMessage('Chyba: Neplatný příspěvek.', 'error');
            $this->redirect('this');
        }

        $post = $this->postFacade->getPostById($postId);
        if (!$post) {
            $this->flashMessage('Příspěvek neexistuje.', 'error');
            $this->redirect('Homepage:');
        }

        // ARCHIVED: komentáře zakázány
        if ($post->status === 'ARCHIVED') {
            $this->flashMessage('Na tento příspěvek nelze přidávat komentáře.', 'error');
            $this->redirect('this');
        }

        // CLOSED: komentáře pouze pro přihlášené
        if ($post->status === 'CLOSED' && !$this->getUser()->isLoggedIn()) {
            $this->flashMessage('Pro přidání komentáře k tomuto příspěvku se musíte přihlásit.', 'error');
            $this->redirect('this');
        }

        $this->commentFacade->addComment($postId, $values->name, $values->email, $values->content);
        $this->flashMessage('Komentář byl přidán.', 'success');
        $this->redirect('this');
    }
}
