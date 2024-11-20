<?php
namespace App\UI\Post;

use App\Model\PostFacade;
use Nette;
use Nette\Application\UI\Form;

final class PostPresenter extends Nette\Application\UI\Presenter
{
    private PostFacade $facade;

    public function __construct(PostFacade $facade)
    {
        parent::__construct();
        $this->facade = $facade;
    }

    public function renderShow(int $id): void
    {
        $post = $this->facade->getPostById($id);
        if (!$post) {
            $this->error('Stránka nebyla nalezena');
        }
        $this->template->post = $post;
        $this->template->comments = $this->facade->getCommentsByPostId($id);
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
        $postId = $this->getParameter('id');
        if ($postId) {
            $this->facade->addComment($postId, $data);

            $this->flashMessage('Děkuji za komentář', 'success');
            $this->redirect('this');
        } else {
            $this->error('Neplatné ID příspěvku.');
        }
    }
}
