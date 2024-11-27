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
}
