<?php
namespace App\UI\Post;

use App\Model\PostFacade;
use App\Model\CategoryFacade;
use Nette;
use Nette\Application\UI\Form;
use Nette\Http\FileUpload;

final class PostPresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private PostFacade $postFacade,
        private CategoryFacade $categoryFacade,
    ) {
    }

    public function renderDefault(): void
    {
        $posts = $this->postFacade->getPosts();
        $postList = [];
    
        foreach ($posts as $post) {
            $postList[] = [
                'id' => $post->id,
                'title' => $post->title,
                'content' => $post->content,
                'image' => $post->image,
                'category_id' => $post->category_id,
                'category_name' => $this->categoryFacade->getCategoryNameById($post->category_id),
            ];
        }
    
        $this->template->posts = $postList;
    }
    
    

    public function renderEdit(int $id): void
    {
        $post = $this->postFacade->getPostById($id);
        if (!$post) {
            $this->error('Příspěvek nebyl nalezen');
        }
    
        // Předání výchozích hodnot do formuláře
        $this['postForm']->setDefaults([
            'title' => $post->title,
            'content' => $post->content,
            'category_id' => $post->category_id,
        ]);
    
        // Převod $post na pole a přidání category_name
        $postData = [
            'id' => $post->id,
            'title' => $post->title,
            'content' => $post->content,
            'category_id' => $post->category_id,
            'category_name' => $this->categoryFacade->getCategoryNameById($post->category_id),
        ];
    
        $this->template->post = $postData;
    }
    
    

    public function renderCreate(): void
    {
        $this->template->categories = $this->categoryFacade->getCategories();
    }

    public function renderShow(int $id): void
    {
        $post = $this->postFacade->getPostById($id);
    
        if (!$post) {
            $this->error('Příspěvek nebyl nalezen');
        }
    
        // Přidání názvu kategorie k postu
        $postData = [
            'id' => $post->id,
            'title' => $post->title,
            'content' => $post->content,
            'image' => $post->image,
            'category_id' => $post->category_id,
            'category_name' => $this->categoryFacade->getCategoryNameById($post->category_id),
        ];
    
        $this->template->post = $postData;
    }
    


    protected function createComponentPostForm(): Form
    {
        $form = new Form;
        $form->addText('title', 'Název')->setRequired();
        $form->addTextArea('content', 'Obsah')->setRequired();
        
        $form->addSelect('category_id', 'Kategorie', $this->categoryFacade->getCategoriesName())
            ->setPrompt('Vyber kategorii')
            ->setRequired();
    
        $form->addUpload('image', 'Obrázek')
            ->setRequired(false)
            ->addRule(Form::IMAGE, 'Obrázek musí být ve formátu JPEG, PNG nebo GIF');
    
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
            $this->postFacade->updatePost($this->getParameter('id'), $values->title, $values->content, $values->category_id, $imagePath);
            $this->flashMessage('Příspěvek byl upraven.', 'success');
        } else {
            $this->postFacade->addPost($values->title, $values->content, $values->category_id, $imagePath);
            $this->flashMessage('Příspěvek byl přidán.', 'success');
        }

        $this->redirect('default');
    }

    public function handleDelete(int $id): void
    {
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
    


}
