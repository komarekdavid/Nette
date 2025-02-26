<?php
namespace App\UI\Genre;

use App\Model\GenreFacade;
use Nette;
use Nette\Application\UI\Form;

final class GenrePresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private GenreFacade $genreFacade,
    ) {
    }

    
    public function renderDefault(): void
    {
        $this->template->genres = $this->genreFacade->getGenres();
    }
    

    public function renderEdit(int $id): void
    {
        $genre = $this->genreFacade->getGenreById($id);
        if (!$genre) {
            $this->error('Žánr nebyl nalezen');
        }

        $this['genreForm']->setDefaults([
            'name' => $genre->name,
        ]);

        $this->template->genre = $genre;
    }

    public function renderCreate(): void
    {
        // Pouze pro vykreslení formuláře
    }

    public function actionDelete(int $id): void
    {
        $genre = $this->genreFacade->getGenreById($id);
        if (!$genre) {
            $this->error('Žánr nebyl nalezen');
        }

        $this->genreFacade->deleteGenre($id);
        $this->flashMessage('Žánr byl smazán.', 'success');
        $this->redirect('default');
    }


    protected function createComponentGenreForm(): Form
    {
        $form = new Form;
        $form->addText('name', 'Název')
            ->setRequired()
            ->addRule(Form::MAX_LENGTH, 'Maximální délka je 255 znaků.', 255);

        $form->addSubmit('save', 'Uložit')
            ->setHtmlAttribute('class', 'genre-button');

        $form->onSuccess[] = function (Form $form, \stdClass $values) {
            if ($this->getParameter('id')) {
                $this->genreFacade->updateGenre($this->getParameter('id'), $values->name);
                $this->flashMessage('Žánr byl upraven.', 'success');
            } else {
                $this->genreFacade->addGenre($values->name);
                $this->flashMessage('Žánr byl přidán.', 'success');
            }
            $this->redirect('default');
        };

        return $form;
    }
}