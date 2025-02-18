<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Entity\Article;

class ArticleController extends Controller
{
    public function route(): void
    {
        try {
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'list':
                        $this->list();
                        break;
                    case 'show':
                        $this->show();
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

    protected function list(): void
    {
        try {
            $articleRepository = new ArticleRepository();
            $articles = $articleRepository->findAll();

            $this->render('article/list', [
                'articles' => $articles,
                'pageTitle' => 'Liste des articles'
            ]);

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
                throw new \Exception("ID d'article invalide.");
            }
    
            $articleRepository = new ArticleRepository();
            $commentRepository = new CommentRepository();


            //if (isset($_POST))
    
            $article = $articleRepository->findOneById((int) $_GET['id']);
            $comments = $commentRepository->findByArticleId((int) $_GET['id']);
    
            if (!$article) {
                throw new \Exception("Article non trouvé.");
            }
    
            $this->render('article/show', [
                'article' => $article,
                'comments' => $comments,
                'pageTitle' => $article->getTitle()
            ]);
        } catch (\Exception $e) {
            $this->render('errors/default', [
                'error' => $e->getMessage()
            ]);
        }
    }
};    