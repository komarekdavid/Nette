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
        $form->addUpload('image', 'Obrázek:')
            ->setRequired('Prosím nahrajte obrázek.')
            ->addRule(Form::IMAGE, 'Obrázek musí být ve formátu JPEG, PNG nebo GIF.');

        $form->addSubmit('send', 'Uložit a publikovat');
        $form->onSuccess[] = [$this, 'postFormSucceeded'];

        return $form;
    }

    public function postFormSucceeded(Form $form, array $data): void
    {
        $id = $this->getParameter('id');

        // Zpracování nahraného souboru
        if ($data['image']->isOk() && $data['image']->isImage()) {
            $imageName = $data['image']->getSanitizedName();
            $imagePath = 'upload/' . $imageName;

            try {
                $data['image']->move($imagePath);
                $data['image'] = $imagePath; // Uložení cesty do dat
            } catch (Nette\Utils\ImageException $e) {
                $this->flashMessage('Nepodařilo se nahrát obrázek.', 'error');
                $this->redirect('this');
            }
        } else {
            $this->flashMessage('Obrázek nebyl nahrán. Zkontrolujte formát souboru.', 'error');
            $this->redirect('this');
        }

        // Uložení dat
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