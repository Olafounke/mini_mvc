<?php require_once _ROOTPATH_ . '/templates/header.php'; ?>

<h1><?= htmlspecialchars($article->getTitle()) ?></h1>

<p><?= nl2br(htmlspecialchars($article->getDescription())) ?></p>

<h2>Commentaires</h2>

<?php if (!empty($comments)): ?>
    <ul>
        <?php foreach ($comments as $comment): ?>
            <li>
                <?php
                $fullComment = htmlspecialchars($comment->getComment());
                $preview = implode(' ', array_slice(explode(' ', $fullComment), 0, 10)); // Limite à 10 mots
                ?>
                <?= $preview ?> 
                
                <?php if (str_word_count($fullComment) > 10): ?>
                    ... <a href="index.php?controller=comment&action=show&id=<?= $comment->getId() ?>">Lire plus</a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun commentaire pour cet article.</p>
<?php endif; ?>

<h2>Ajouter un commentaire</h2>

<!-- Incomplet -->

<form method="post" action="index.php?controller=article&action=show">
    <textarea name="comment" required></textarea>
    <input type="hidden" name="article_id" value="<?= $article->getId() ?>">
    <input type="hidden" name="user_id" value="1">
    <button type="submit">Envoyer</button>
</form>

<a href="index.php?controller=article&action=list">← Retour à la liste</a>

<?php require_once _ROOTPATH_ . '/templates/footer.php'; ?>
