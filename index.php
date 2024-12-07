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

        <meta name="description" content="Турниры по CS:GO от МажорныеОбстоятельства. Узнайте больше о крупнейших событиях и участвуйте в обсуждениях.">
        <meta name="keywords" content="Киберспорт, CS:GO турниры, МажорныеОбстоятельства, киберспортивные события, Counter-Strike">
        <meta name="author" content="МажорныеОбстоятельства">
        <meta property="og:title" content="МажорныеОбстоятельства - Турниры CS:GO">
        <meta property="og:description" content="Самые интересные события и турниры по CS:GO. Присоединяйтесь и участвуйте!">
        
        <link rel="icon" type="image/png" href="assets/favicon.png">
        <link rel="stylesheet" href="mazhor.styles.css">
    </head>
    <body>
        <div class="main_container">
            <div class="container">
                <h1 class="container_header">Что такое Мажорные Обстоятельства?</h1>
                <p class="container_content">
                    Ну, в общем, как было: собрались челики лет 16, решили поиграть, ну и устроили турнир. В итоге получилось довольно прикольно в CS:S(ксгосурс). Ну, в общем, сюда будем писать как в хз, пока хз что, но да? прикольно же
                    <br><br>Вообще, файер ин зе холл — что тут еще сказать. Следующее, что нужно знать — здесь будут посты коментариев не будет сасите, а так общайтесь где-то хз где или заходите поиграть с нами или не с нами
                </p>
            </div>

            <div id="posts" class="container_divider">
                <?php foreach ($posts as $post): ?>
                    <div class="container">
                        <h2 class="container_header subheader"><?= secure_input($post['header']) ?></h2>
                        <p class="container_content"><?= nl2br(secure_input($post['content'])) ?></p>
                        <?php if (!empty($post['image'])): ?>
                            <img src="<?= secure_input($post['image']) ?>" alt="Изображение поста" class="container_image">
                        <?php endif; ?>
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
