<?php
namespace App\Model;

use Nette\Database\Explorer;

final class PostFacade
{
    public function __construct(
        private Explorer $database,
    ) {
    }

    public function getPosts()
    {
        return $this->database->table('posts')
            ->order('created_at DESC')
            ->fetchAll();
    }

    public function getPostById(int $id)
    {
        return $this->database->table('posts')->get($id);
    }

    public function addPost(string $title, string $content, int $category_id, ?string $image = null): void
    {
        $this->database->table('posts')->insert([
            'title' => $title,
            'content' => $content,
            'category_id' => $category_id,
            'image' => $image,
            'created_at' => new \DateTime(),
        ]);
    }

    public function updatePost(int $id, string $title, string $content, int $category_id, ?string $image = null): void
    {
        $data = [
            'title' => $title,
            'content' => $content,
            'category_id' => $category_id,
        ];

        if ($image !== null) {
            $data['image'] = $image;
        }

        $this->database->table('posts')->where('id', $id)->update($data);
    }

    public function deletePost(int $id): void
    {
        $this->database->table('posts')->where('id', $id)->delete();
    }

    public function getPublicArticles()
    {
        return $this->database->table('posts')
            ->where('category_id IS NOT NULL')  
            ->order('created_at DESC')
            ->fetchAll();
    }

}
