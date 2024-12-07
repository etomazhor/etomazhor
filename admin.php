<?php

session_set_cookie_params([
    'lifetime' => 3600,
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict',
]);
session_start();

require 'config.php';

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}

// Генерация CSRF токена
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверка CSRF токена
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        die('CSRF токен неверен.');
    }

    $header = secure_input($_POST['header']);
    $content = secure_input($_POST['content']);

    $stmt = $pdo->prepare("INSERT INTO posts (header, content, created_at) VALUES (:header, :content, NOW())");
    $stmt->execute([':header' => $header, ':content' => $content]);

    echo 'Пост добавлен!';
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <header>Админ-панель</header>
</head>
<body>
    <h1>Добавить новый пост</h1>
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= secure_input($_SESSION['csrf_token']) ?>">
        <label for="header">Заголовок:</label>
        <input type="text" name="header" id="header" required>
        <br>
        <label for="content">Содержание:</label>
        <textarea name="content" id="content" required></textarea>
        <br>
        <button type="submit">Опубликовать</button>
    </form>
</body>
</html>
