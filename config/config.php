<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'blog');
define('DB_USER', 'root');
define('DB_PASS', '');

// Хэш пароля администратора, bcrypt вроде здесь туда сюда да
define('ADMIN_PASSWORD_HASH', '$2a$12$PNxYTSB1zR11/S0BmqWZluWVc/qDmSg6XhsX0V.iPQdsJNBnwA3Ey');

try { // Подключения через PDO - ПедроДедОпозиция
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) { die("Ошибка подключения к базе данных."); }

// Строгий контроль ошибок, хз че это вообще
function secure_input($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}
