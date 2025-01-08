<?php

namespace App\UI\Game;

use App\Model\GameFacade;
use Nette;

final class GamePresenter extends Nette\Application\UI\Presenter
{
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
}
