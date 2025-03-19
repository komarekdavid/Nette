<?php

namespace App\Model;

use Nette\Database\Explorer;

final class PostFacade
{
    public function __construct(
        private Explorer $database,
    ) {}

    public function getPosts(bool $onlyPublic = false)
    {
        $sql = 'SELECT * FROM posts ORDER BY created_at DESC';
        
        if ($onlyPublic) {
            $sql .= ' WHERE status = ?';
            return $this->database->query($sql, 'PUBLISHED')->fetchAll();
        }

        return $this->database->query($sql)->fetchAll();
    }

    public function getPostById(int $id)
    {
        $sql = 'SELECT * FROM posts WHERE id = ?';
        return $this->database->query($sql, $id)->fetch();
    }

    public function getPostsWithCategoryName(bool $onlyPublic = false)
    {
        $sql = 'SELECT posts.*, categories.name AS category_name
                FROM posts
                LEFT JOIN categories ON posts.category_id = categories.id
                ORDER BY created_at DESC';
        
        if ($onlyPublic) {
            $sql .= ' WHERE posts.status = ?';
            return $this->database->query($sql, 'PUBLISHED')->fetchAll();
        }

        return $this->database->query($sql)->fetchAll();
    }

    public function addPost(string $title, string $content, int $category_id, ?string $image = null, string $status = 'DRAFT'): void
    {
        $sql = 'INSERT INTO posts (title, content, category_id, image, created_at, status) VALUES (?, ?, ?, ?, ?, ?)';
        $this->database->query($sql, $title, $content, $category_id, $image, new \DateTime(), $status);
    }

    public function updatePost(int $id, string $title, string $content, int $category_id, ?string $image = null, string $status = 'DRAFT'): void
    {
        $sql = 'UPDATE posts SET title = ?, content = ?, category_id = ?, status = ?' . ($image ? ', image = ?' : '') . ' WHERE id = ?';
        if ($image) {
            $this->database->query($sql, $title, $content, $category_id, $status, $image, $id);
        } else {
            $this->database->query($sql, $title, $content, $category_id, $status, $id);
        }
    }

    public function deletePost(int $id): void
    {
        $sql = 'DELETE FROM posts WHERE id = ?';
        $this->database->query($sql, $id);
    }

    public function deleteImage(int $id): void
    {
        $sql = 'SELECT image FROM posts WHERE id = ?';
        $post = $this->database->query($sql, $id)->fetch();

        if ($post && $post->image) {
            $imagePath = __DIR__ . '/../../www/' . $post->image;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $sql = 'UPDATE posts SET image = NULL WHERE id = ?';
        $this->database->query($sql, $id);
    }

    public function incrementViews(int $id): void
    {
        $sql = 'UPDATE posts SET views_count = views_count + 1 WHERE id = ?';
        $this->database->query($sql, $id);
    }

    public function getCategories(): array
    {
        return $this->database->table('categories')->fetchAll();
    }

}
