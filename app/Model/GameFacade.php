<?php

namespace App\Model;

use Nette\Database\Explorer;

final class GameFacade
{
    public function __construct(
        private Explorer $database,
    ) {
    }

    public function getGamesWithGenres()
    {
        return $this->database->query('
            SELECT games.*, genres.name AS genre_name
            FROM games
            LEFT JOIN genres ON genres.id = games.genre_id
            ORDER BY games.created_at DESC
        ')->fetchAll();
    }
    
    
    public function getGameById(int $id)
    {
        return $this->database->query('
            SELECT games.*, genres.name AS genre_name
            FROM games
            LEFT JOIN genres ON genres.id = games.genre_id
            WHERE games.id = ?
        ', $id)->fetch();
    }

    public function getGamesByGenre(int $genreId)
    {
        return $this->database->query("
            SELECT games.*, genres.name AS genre_name
            FROM games
            LEFT JOIN genres ON genres.id = games.genre_id
            WHERE games.genre_id = ?
        ", $genreId)->fetchAll();
    }
    
    

    
    

    public function createGame(array $data)
    {
        $this->database->table('games')->insert($data);
    }

    public function deleteGame(int $id): void
    {
        $this->database->table('games')->where('id', $id)->delete();
    }

    public function updateGame(int $id, array $data)
    {
        $this->database->table('games')->where('id', $id)->update($data);
    }
}
