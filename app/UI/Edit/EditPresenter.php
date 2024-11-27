<?php
namespace App\UI\Edit;

use App\Model\PostFacade;
use Nette;
use Nette\Application\UI\Form;

final class EditPresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private PostFacade $facade,
    ) {
    }

    protected function createComponentPostForm(): Form
    {
        $form = new Form;
        $form->addText('title', 'Titulek:')
            ->setRequired();
        $form->addTextArea('content', 'Obsah:')
            ->setRequired();

        $form->addSubmit('send', 'Uložit a publikovat');
        $form->onSuccess[] = $this->postFormSucceeded(...);

        return $form;
    }

    private function postFormSucceeded(array $data): void
    {
        $id = $this->getParameter('id');

        $post = $this->facade->savePost($data, $id);

        $this->flashMessage('Příspěvek byl úspěšně publikován.', 'success');
        $this->redirect('Post:show', $post->id);
    }

    public function renderEdit(int $id): void
    {
        $post = $this->facade->getPostById($id);

        if (!$post) {
            $this->error('Příspěvek nebyl nalezen');
        }

        $this->getComponent('postForm')
            ->setDefaults($post->toArray());
    }

    public function startup(): void
    {
        parent::startup();

        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }
    }



}
