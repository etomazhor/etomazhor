<?php
session_set_cookie_params([
    'lifetime' => 3600,
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict',
]); session_start();

include 'config/config.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php'); exit;
}

// Генерация CSRF токена, тоже хз что это но что-то с security
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверка CSRF токена опять таки я не прочитал мне пофиг
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) { die('CSRF токен неверен.'); }

    $header = secure_input($_POST['header']);
    $content = secure_input($_POST['content']);

    $stmt = $pdo->prepare("INSERT INTO posts (header, content, created_at) VALUES (:header, :content, NOW())");
    $stmt->execute([':header' => $header, ':content' => $content]);

    echo 'Пост добавлен!'; // это можно удалить да потом
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>А я вижу ты тут главный да?</title>
    <link rel="icon" type="image/png" href="assets/favicon.png">
    <link rel="stylesheet" href="mazhor.styles.css">
</head>
<body>
    <div class="main_container">
        <div class="container">
            <h1 class="container_header">Админ панель</h1>
            <p class="container_content">Это админ панель йоу</p>
        </div>

        <div class="container">
            <h1 class="container_header">Админ панель</h1>
            <p class="container_content">Это админ панель йоу</p>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= secure_input($_SESSION['csrf_token']) ?>">
                <label class="container_form" for="header">Заголовок:</label><br>
                <input class="container_input" type="text" name="header" id="header" required><br>
                <label class="container_form" for="content">Содержание:</label><br>
                <textarea rows="4" class="container_input" name="content" id="content" required></textarea><br>
                <button class="container_input container_button" type="submit">Опубликовать</button>
            </form>
        </div>
    </div>
</body>
</html>
