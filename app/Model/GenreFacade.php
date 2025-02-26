<?php
namespace App\Model;

use Nette\Database\Explorer;

final class GenreFacade
{
    public function __construct(
        private Explorer $database,
    ) {
    }

    public function getGenres()
    {
        return $this->database->table('genres')->order('id DESC')->fetchAll();
    }

    public function getGenresName(): array
    {
        return $this->database->table('genres')
            ->fetchPairs('id', 'name'); 
    }
    

    public function getGenreById(int $id)
    {
        return $this->database->table('genres')->get($id);
    }

    public function addGenre(string $name, ?string $description = null): void
    {
        $this->database->table('genres')->insert([
            'name' => $name,
        ]);
    }

    public function updateGenre(int $id, string $name, ?string $description = null): void
    {
        $this->database->table('genres')->where('id', $id)->update([
            'name' => $name,
        ]);
    }

    public function deleteGenre(int $id): void
    {
        $this->database->table('genres')->where('id', $id)->delete();
    }
}