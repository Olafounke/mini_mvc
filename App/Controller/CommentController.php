<?php

namespace App\Controller;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Entity\Comment;

class CommentController extends Controller
{
    public function route(): void
    {
        try {
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'show':
                        $this->show();
                        break;
                    case 'add':
                        $this->add();
                        break;
                    default:
                        throw new \Exception("Cette action n'existe pas : " . $_GET['action']);
                }
            } else {
                throw new \Exception("Aucune action détectée");
            }
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        }
    }

    protected function show(): void
{
    try {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            throw new \Exception("ID de commentaire invalide.");
        }

        $commentRepository = new CommentRepository();
        $comment = $commentRepository->findOneById((int) $_GET['id']);

        if (!$comment) {
            throw new \Exception("Commentaire non trouvé.");
        }

        $this->render('comment/show', [
            'comment' => $comment,
            'pageTitle' => 'Détail du commentaire'
        ]);
    } catch (\Exception $e) {
        $this->render('errors/default', [
            'error' => $e->getMessage()
        ]);
    }
}

protected function add(): void
{
    try {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'], $_POST['article_id'], $_POST['user_id'])) {
            $content = trim($_POST['content']);
            $articleId = (int) $_POST['article_id'];
            $userId = (int) $_POST['user_id'];

            if (empty($content)) {
                throw new \Exception("Le commentaire ne peut pas être vide.");
            }

            $comment = new Comment();
            $comment->setComment($content)
                    ->setArticleId($articleId)
                    ->setUserId($userId);

            $commentRepository = new CommentRepository();
            $commentRepository->save($comment);

            // Redirection vers l'article après ajout du commentaire
            header("Location: index.php?controller=article&action=show&id=" . $articleId);
            exit;
        } else {
            throw new \Exception("Données invalides pour ajouter un commentaire.");
        }
    } catch (\Exception $e) {
        $this->render('errors/default', [
            'error' => $e->getMessage()
        ]);
    }
}

}
