<?php

session_start();
require 'config.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5; // Количество постов на странице
$offset = ($page - 1) * $limit;

// Получение постов из базы данных
$stmt = $pdo->prepare("SELECT * FROM posts ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная страница</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; }
        .post { border-bottom: 1px solid #ddd; margin-bottom: 20px; padding-bottom: 10px; }
        #loadMore { cursor: pointer; color: blue; text-align: center; }
    </style>
</head>
<body>
    <h1>Посты</h1>
    <div id="posts">
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <h2><?= htmlspecialchars($post['header']) ?></h2>
                <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                <small>Опубликовано: <?= htmlspecialchars($post['created_at']) ?></small>
            </div>
        <?php endforeach; ?>
    </div>
    <div id="loadMore">Загрузить еще</div>

    <script>
        let page = <?= $page ?>;
        document.getElementById('loadMore').addEventListener('click', function() {
            page++;
            fetch(`load_posts.php?page=${page}`)
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === '') {
                        document.getElementById('loadMore').innerText = 'Больше постов нет';
                        return;
                    }
                    document.getElementById('posts').innerHTML += data;
                });
        });
    </script>
</body>
</html>
