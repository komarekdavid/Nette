<?php
namespace App\Model;

use Nette;

final class PostFacade
{
    public function __construct(
        private Nette\Database\Explorer $database,
    ) {
    }

    public function getPublicArticles()
    {
        return $this->database
            ->table('posts')
            ->where('created_at < ', new \DateTime)
            ->order('created_at DESC');
    }

    public function getPostById(int $id)
    {
        return $this->database
            ->table('posts')
            ->get($id);
    }

    public function getCommentsByPostId(int $postId)
    {
        return $this->database
            ->table('comments')
            ->where('post_id', $postId)
            ->order('created_at');
    }

    public function addComment(int $postId, \stdClass $data): void
    {
        $this->database->table('comments')->insert([
            'post_id' => $postId,
            'name' => $data->name,
            'email' => $data->email,
            'content' => $data->content,
        ]);
    }
}
