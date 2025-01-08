<?php

namespace App\Model;

use Nette\Database\Explorer;

final class GameFacade
{
    public function __construct(
        private Explorer $database,
    ) {
    }

    public function getGames()
    {
        return $this->database
            ->table('games')
            ->order('created_at DESC')
            ->fetchAll();
    }

    public function getGameById(int $id)
    {
        return $this->database->table('games')->get($id);
    }
}
