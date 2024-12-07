<?php
session_set_cookie_params([
    'lifetime' => 3600,
    'secure' => false,
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
                <h1 class="container_header">Если ты не администратор</h1>
                <p class="container_content">
                    Если ты не администратор и будешь щас питаться подобрать пароль, то я сочувствую тебе реально, иди лучше в кс поиграй я хз или я хз ну вообщем не нада такое что-то делать ты понял, один раз говорою потом будет турик 1 на 1 с тобой, стрела так сказать, 52 это второй ты понял
                </p>
            </div>

            <div class="container">
                <h1 class="container_header">Вход администратора</h1>
                <p class="container_content">
                    Юра блять, если ты забыл пароль то вспомни да это самое ну ты понял да, ну я хз как сказать не могу же я сюда пароль написать, ну вообщем да да
                </p>

                <form method="POST">
                    <label class="container_form" for="password">Пароль ниже вписать нужно:</label><br>
                    <input class="container_input" type="password" name="password" id="password" required><br>
                    <button class="container_button container_input" type="submit">Войти в админа</button>
                </form>
            </div>
        </div>
    </body>
</html>
