<?php


session_set_cookie_params([
    'lifetime' => 3600,
    'secure' => true, // Только HTTPS
    'httponly' => true, // Закрывает доступ из JS
    'samesite' => 'Strict', // Защита от CSRF
]);
session_start();

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';

    if (password_verify($password, ADMIN_PASSWORD_HASH)) {
        session_regenerate_id(true); // Предотвращает угон сессии
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $error = 'Неверный пароль!';
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход администратора</title>
</head>
<body>
    <h1>Вход администратора</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= secure_input($error) ?></p>
    <?php endif; ?>
    <form method="POST">
        <label for="password">Пароль:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Войти</button>
    </form>
</body>
</html>
