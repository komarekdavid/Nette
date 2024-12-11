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

    public function savePost(array $data, ?int $id = null)
    {
        if ($id) {
            $post = $this->database->table('posts')->get($id);
            $post->update($data);
        } else {
            $post = $this->database->table('posts')->insert($data);
        }

        return $post;
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

    public function getCommentById(int $commentId)
    {
        return $this->database->table('comments')->get($commentId);
    }

    public function deleteComment(int $commentId): void
    {
        $comment = $this->database->table('comments')->get($commentId);
        if ($comment) {
            $comment->delete();
        }
    }
    

    
}
