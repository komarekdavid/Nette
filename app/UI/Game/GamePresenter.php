<?php

namespace App\UI\Game;

use App\Model\GameFacade;
use Nette;

final class GamePresenter extends Nette\Application\UI\Presenter
{

    private ?int $editingGameId = null;

    
    public function __construct(
        private GameFacade $facade,
    ) {
    }

    public function renderDefault(): void
    {
        $this->redirect('list');
    }


    public function renderList(): void
    {
        $this->template->games = $this->facade->getGames();
    }

    public function renderDescription(int $id): void
    {
        $game = $this->facade->getGameById($id);
        if (!$game) {
            $this->error('Hra nebyla nalezena.');
        }

        $this->template->game = $game;
    }

    public function renderCreate(): void
    {

    }

    public function createComponentGameForm(): Nette\Application\UI\Form
    {
        $form = new Nette\Application\UI\Form;

        $form->addText('name', 'Název:')
            ->setRequired('Zadejte název hry.');

        $form->addTextArea('description', 'Popis:')
            ->setRequired('Zadejte popis hry.');

        $form->addSubmit('send', 'Uložit');

        $form->onSuccess[] = function (Nette\Application\UI\Form $form, \stdClass $values): void {
            if ($this->editingGameId) {
                $this->facade->updateGame($this->editingGameId, (array) $values);
                $this->flashMessage('Hra byla úspěšně upravena.', 'success');
                $this->redirect('Game:list');
            } else {
                $data = [
                    'name' => $values->name,
                    'description' => $values->description,
                    'created_at' => new \DateTime(),
                ];
                $this->facade->createGame($data);
                $this->flashMessage('Hra byla úspěšně vytvořena.', 'success');
                $this->redirect('Game:list');
            }
        };

        return $form;
    }



    public function handleDeleteGame(int $id): void
    {
        $game = $this->facade->getGameById($id);

        if ($game) {
            $this->facade->deleteGame($id);
            $this->flashMessage('Hra byla úspěšně smazána.', 'success');
        } else {
            $this->flashMessage('Hra nebyla nalezena nebo již byla smazána.', 'error');
        }

        $this->redirect('Game:list');
    }

    public function renderEdit(int $id): void
    {
        $game = $this->facade->getGameById($id);
        if (!$game) {
            $this->error('Hra nebyla nalezena.');
        }

        $this->editingGameId = $id;
        $this['gameForm']->setDefaults([
            'name' => $game->name,
            'description' => $game->description,
        ]);

        $this->template->game = $game;
    }

    




}
