<?php require_once _ROOTPATH_ . '/templates/header.php'; ?>

<h1>Liste des articles</h1>

<?php if (!empty($articles)): ?>
    <ul>
        <?php foreach ($articles as $article): ?>
            <li>
                <h2><?= htmlspecialchars($article->getTitle()) ?></h2>
                <a href="index.php?controller=article&action=show&id=<?= $article->getId() ?>">Lire plus</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun article trouvé.</p>
<?php endif; ?>

<?php require_once _ROOTPATH_ . '/templates/footer.php'; ?>
