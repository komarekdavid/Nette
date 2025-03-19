<?php
namespace App\Model;

use Nette\Database\Explorer;

final class CommentFacade
{
    public function __construct(
        private Explorer $database,
    ) {
    }

    public function getCommentsByPost(int $postId)
    {
        return $this->database->table('comments')
            ->where('post_id', $postId)
            ->order('created_at DESC')
            ->fetchAll();
    }

    public function addComment(int $postId, string $name, string $email, string $content): void
    {
        $this->database->table('comments')->insert([
            'post_id' => $postId,
            'name' => $name,
            'email' => $email,
            'content' => $content,
            'created_at' => new \DateTime(),
        ]);
    }
    
    public function deleteComment(int $commentId, int $userId): void
    {
        $this->database->table('comments')
            ->where('id', $commentId)
            ->where('user_id', $userId)
            ->delete();
    }
}
