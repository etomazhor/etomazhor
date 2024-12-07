<?php


// Подключение к базе данных с минимальными привилегиями
define('DB_HOST', 'localhost');
define('DB_NAME', 'blog');
define('DB_USER', 'root'); // пользователь с правами на SELECT, INSERT
define('DB_PASS', '');

// Хэш пароля администратора (сгенерируйте заранее и вставьте)
define('ADMIN_PASSWORD_HASH', '$2a$12$PNxYTSB1zR11/S0BmqWZluWVc/qDmSg6XhsX0V.iPQdsJNBnwA3Ey');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных.");
}

// Строгий контроль ошибок
function secure_input($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}
