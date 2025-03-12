<?php

namespace App\UI\Game;

use App\Model\GameFacade;
use App\Model\GenreFacade;
use Nette;

final class GamePresenter extends Nette\Application\UI\Presenter
{
    private ?int $editingGameId = null;
    private GameFacade $gameFacade;
    private GenreFacade $genreFacade;

    public function __construct(GameFacade $gameFacade, GenreFacade $genreFacade)
    {
        parent::__construct();
        $this->gameFacade = $gameFacade;
        $this->genreFacade = $genreFacade;
    }

    public function renderDefault(): void
    {
        $this->redirect('list');
    }

    public function renderList(?int $genre = null): void
    {
        $this->template->genres = $this->genreFacade->getGenres();
        $this->template->selectedGenre = $genre;
    
        if ($genre) {
            $this->template->games = $this->gameFacade->getGamesByGenre($genre);
        } else {
            $this->template->games = $this->gameFacade->getGamesWithGenres();
        }
    }
    

    public function renderDescription(int $id): void
    {
        $game = $this->gameFacade->getGameById($id);
        if (!$game) {
            $this->error('Hra nebyla nalezena.');
        }
        $this->template->game = $game;
    }

    public function renderCreate(): void
    {
        $this->template->genres = $this->genreFacade->getGenresName();
    }

    public function createComponentGameForm(): Nette\Application\UI\Form
    {
        $form = new Nette\Application\UI\Form;

        $form->addText('name', 'Název:')
            ->setRequired('Zadejte název hry.');

        $form->addTextArea('description', 'Popis:')
            ->setRequired('Zadejte popis hry.');
        
        $form->addSelect('genre_id', 'Žánr:', $this->genreFacade->getGenresName())
            ->setPrompt('Vyberte žánr')
            ->setRequired('Vyberte žánr hry.');

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = function (Nette\Application\UI\Form $form, \stdClass $values): void {
            $data = [
                'name' => $values->name,
                'description' => $values->description,
                'genre_id' => $values->genre_id,
            ];
            
            if ($this->editingGameId) {
                $this->gameFacade->updateGame($this->editingGameId, $data);
                $this->flashMessage('Hra byla úspěšně upravena.', 'success');
            } else {
                $this->gameFacade->createGame($data);
                $this->flashMessage('Hra byla úspěšně vytvořena.', 'success');
            }
            
            $this->redirect('Game:list');
        };

        return $form;
    }

    public function renderEdit(int $id): void
    {
        $game = $this->gameFacade->getGameById($id);
        if (!$game) {
            $this->error('Hra nebyla nalezena.');
        }

        $this->editingGameId = $id;
        $this['gameForm']->setDefaults([
            'name' => $game->name,
            'description' => $game->description,
            'genre_id' => $game->genre_id,
        ]);

        $this->template->game = $game;
        $this->template->genres = $this->genreFacade->getGenresName();
    }

    public function actionDelete(int $id): void
    {
        $this->gameFacade->deleteGame($id);
        $this->flashMessage('Hra byla úspěšně smazána.', 'success');
        $this->redirect('Game:list');
    }
}
