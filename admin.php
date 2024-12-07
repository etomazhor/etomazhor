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
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) { die('CSRF токен неверен.'); }

    $header = secure_input($_POST['header']);
    $content = secure_input($_POST['content']);

    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/'; // Папка для загрузки изображений
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($_FILES['image']['tmp_name']);

        if (!in_array($fileType, $allowedTypes)) {
            die('Неверный формат файла. Допустимые форматы: JPEG, PNG, GIF.');
        }

        $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
        $imagePath = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) { die('Ошибка загрузки файла.'); }
    }

    $stmt = $pdo->prepare("INSERT INTO posts (header, content, image, created_at) VALUES (:header, :content, :image, NOW())");
    $stmt->execute([
        ':header' => $header,
        ':content' => $content,
        ':image' => $imagePath,
    ]);
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
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= secure_input($_SESSION['csrf_token']) ?>">
                <label class="container_form" for="header">Заголовок:</label><br>
                <input class="container_input" type="text" name="header" id="header" required><br>
                <label class="container_form" for="content">Содержание:</label><br>
                <textarea rows="4" class="container_input" name="content" id="content" required></textarea><br>
                <label class="container_form" for="image">Изображение:</label><br>
                <input class="container_input" type="file" name="image" id="image" accept="image/*"><br>
                <button class="container_input container_button" type="submit">Опубликовать</button>
            </form>
        </div>
    </div>
</body>
</html>
