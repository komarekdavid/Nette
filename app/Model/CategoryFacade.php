<?php
namespace App\Model;

use Nette\Database\Explorer;

final class CategoryFacade
{
    public function __construct(
        private Explorer $database,
    ) {
    }

    public function getCategories()
    {
        return $this->database->table('category')->order('id DESC')->fetchAll();
    }

    public function getCategoriesName(): array
    {
        return $this->database->table('category')
            ->fetchPairs('id', 'name'); 
    }
    

    public function getCategoryById(int $id)
    {
        return $this->database->table('category')->get($id);
    }

    public function addCategory(string $name, ?string $description = null): void
    {
        $this->database->table('category')->insert([
            'name' => $name,
            'description' => $description,
            'created_at' => new \DateTime(),
        ]);
    }

    public function updateCategory(int $id, string $name, ?string $description = null): void
    {
        $this->database->table('category')->where('id', $id)->update([
            'name' => $name,
            'description' => $description,
        ]);
    }

    public function deleteCategory(int $id): void
    {
        $this->database->table('category')->where('id', $id)->delete();
    }


    public function getCategoryNameById(int $id): string
    {
        return $this->database->table('category')->get($id)->name ?? 'Neznámá kategorie';
    }

}
