<?php
session_start(); // Запускаем сессию вообщем да
include 'config/config.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5; // Количество постов на странице
$offset = ($page - 1) * $limit;

// Получение постов из базы данных, все безопастно все чики пуки
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
    <title>МажорныеОбстоятельства Турниры КС:С</title>
    <link rel="icon" type="image/png" href="assets/favicon.png">
    <link rel="stylesheet" href="mazhor.styles.css">
</head>
<body>
    <div class="main_container">
        <div class="container">
            <h1 class="container_header">What is 4chan?</h1>
            <p class="container_content">4chan is a simple image-based bulletin board where anyone can post comments and share images. There are boards dedicated to a variety of topics, from Japanese animation and culture to videogames, music, and photography. Users do not need to register an account before participating in the community. Feel free to click on a board below that interests you and jump right in!</p>
        </div>

        <div id="posts" class="container_divider">
            <?php foreach ($posts as $post): ?>
                <div class="container">
                    <h1 class="container_header subheader"><?= htmlspecialchars($post['header']) ?></h1>
                    <p class="container_content"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div id="load_more" class="load_more">Загрузить еще</div>
    </div>

    <script>
        let page = <?= $page ?>;
        document.getElementById('load_more').addEventListener('click', function() {
            page++;
            fetch(`load_posts.php?page=${page}`)
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === '') {
                        document.getElementById('load_more').innerText = 'Больше постов нет';
                        return;
                    }
                    document.getElementById('posts').innerHTML += data;
                });
        });
    </script>
</body>
</html>
