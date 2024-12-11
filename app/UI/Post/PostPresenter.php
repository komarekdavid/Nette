<?php
namespace App\UI\Post;

use App\Model\PostFacade;
use Nette;
use Nette\Application\UI\Form;

final class PostPresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private PostFacade $facade,
    ) {
    }

    public function renderShow(int $id): void
    {
        $post = $this->facade->getPostById($id);
        if (!$post) {
            $this->error('Stránka nebyla nalezena');
        }

        $this->template->post = $post;
        $this->template->comments = $this->facade->getCommentsForPost($id);
    }

    protected function createComponentCommentForm(): Form
    {
        $form = new Form;

        $form->addText('name', 'Jméno:')
            ->setRequired();

        $form->addEmail('email', 'E-mail:');

        $form->addTextArea('content', 'Komentář:')
            ->setRequired();

        $form->addSubmit('send', 'Publikovat komentář');

        $form->onSuccess[] = $this->commentFormSucceeded(...);

        return $form;
    }

    private function commentFormSucceeded(\stdClass $data): void
    {
        $id = $this->getParameter('id');

        $this->facade->addComment($id, $data->name, $data->email, $data->content);

        $this->flashMessage('Děkuji za komentář', 'success');
        $this->redirect('this');
    }

    public function handleDeleteImage(int $postId): void
    {
        $post = $this->facade->getPostById($postId);

        if ($post && $post['image']) {
            if (file_exists($post['image'])) {
                unlink($post['image']); // Smazání obrázku z disku
            }

            $data['image'] = null;
            $this->facade->editPost($postId, $data); // Aktualizace databáze
            $this->flashMessage('Obrázek byl úspěšně smazán.', 'success');
        } else {
            $this->flashMessage('Obrázek nebyl nalezen nebo již byl smazán.', 'error');
        }

        $this->redirect('this'); // Přesměrování na aktuální stránku
    }
    
    public function handleDeleteComment(int $commentId): void
    {
        $comment = $this->facade->getCommentById($commentId);

        if ($comment) {
            $this->facade->deleteComment($commentId);
            $this->flashMessage('Komentář byl úspěšně smazán.', 'success');
        } else {
            $this->flashMessage('Komentář nebyl nalezen nebo již byl smazán.', 'error');
        }

        $this->redirect('this'); 
    }

}
