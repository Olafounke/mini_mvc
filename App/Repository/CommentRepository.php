<?php

namespace App\Repository;

use App\Entity\Comment;

class CommentRepository extends Repository
{
    public function findByArticleId(int $articleId): array
    {
        $query = $this->pdo->prepare("
            SELECT * FROM comment 
            WHERE article_id = :article_id 
            ORDER BY id DESC
        ");
        $query->bindValue(':article_id', $articleId, $this->pdo::PARAM_INT);
        $query->execute();

        $comments = $query->fetchAll($this->pdo::FETCH_ASSOC);
        $commentsObjects = [];

        foreach ($comments as $comment) {
            $commentsObjects[] = Comment::createAndHydrate($comment);
        }

        return $commentsObjects;
    }

    public function findOneById(int $id): ?Comment
    {
        $query = $this->pdo->prepare("SELECT * FROM comment WHERE id = :id");
        $query->bindValue(':id', $id, $this->pdo::PARAM_INT);
        $query->execute();
        
        $comment = $query->fetch($this->pdo::FETCH_ASSOC);
        
        return $comment ? Comment::createAndHydrate($comment) : null;
    }

    public function save(Comment $comment): bool
    {
        if ($comment->getId() !== null) {
            // Mise à jour si l'ID existe déjà
            $query = $this->pdo->prepare("
                UPDATE comment 
                SET comment = :comment 
                WHERE id = :id
            ");
            $query->bindValue(':id', $comment->getId(), $this->pdo::PARAM_INT);
        } else {
            // Insertion si c'est un nouveau commentaire
            $query = $this->pdo->prepare("
                INSERT INTO comment (comment, user_id, article_id) 
                VALUES (:comment, :user_id, :article_id)
            ");
            $query->bindValue(':user_id', $comment->getUserId(), $this->pdo::PARAM_INT);
            $query->bindValue(':article_id', $comment->getArticleId(), $this->pdo::PARAM_INT);
        }

        $query->bindValue(':comment', $comment->getComment(), $this->pdo::PARAM_STR);

        return $query->execute();
    }
}
