<?php

require 'config.php';

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = 5;
$offset = ($page - 1) * $limit;

$stmt = $pdo->prepare("SELECT * FROM posts ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll();

foreach ($posts as $post):
?>
    <div class="post">
        <h2><?= secure_input($post['header']) ?></h2>
        <p><?= nl2br(secure_input($post['content'])) ?></p>
        <small>Опубликовано: <?= secure_input($post['created_at']) ?></small>
    </div>
<?php
endforeach;
?>