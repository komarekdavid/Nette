<?php
namespace App\Model;

use Nette\Database\Explorer;

final class PostFacade
{
    public function __construct(
        private Explorer $database,
    ) {
    }

    public function getPublicArticles()
    {
        return $this->database
            ->table('posts')
            ->order('created_at DESC');
    }

    public function getPostById(int $id)
    {
        return $this->database->table('posts')->get($id);
    }


    public function getCommentsForPost(int $postId)
    {
        return $this->database
            ->table('comments')
            ->where('post_id', $postId)
            ->order('created_at');
    }

    public function addComment(int $postId, string $name, ?string $email, string $content): void
    {
        $this->database->table('comments')->insert([
            'post_id' => $postId,
            'name' => $name,
            'email' => $email,
            'content' => $content,
        ]);
    }
}
