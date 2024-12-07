<?php
session_set_cookie_params([
    'lifetime' => 3600,
    'secure' => true,
    'httponly' => true, 
    'samesite' => 'Strict',
]); session_start();

include 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';

    if (password_verify($password, ADMIN_PASSWORD_HASH)) {
        session_regenerate_id(true); // Предотвращает угон(машини) сессии
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin.php');
        exit; // екзит фром виндов плиз
    } else { $error = 'Неверный пароль!'; } // Эту строку удаляем сразу
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>А тебе сюда не это</title>
    <link rel="icon" type="image/png" href="assets/favicon.png">
    <link rel="stylesheet" href="mazhor.styles.css">
</head>
<body>
    <div class="main_container">
        <div class="container">
            <h1 class="container_header">Вход администратора</h1>
            <p class="container_content">dasdasd</p>
            <form method="POST">
                <label class="container_form" for="password">Пароль:</label><br>
                <input class="container_input" type="password" name="password" id="password" required><br>
                <button class="container_button container_input" type="submit">Войти</button>
            </form>
        </div>
    </div>
</body>
</html>
