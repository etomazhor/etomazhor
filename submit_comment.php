<?php
include 'config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
    $post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;

    if (!empty($comment) && $post_id > 0) {
        $stmt = $pdo->prepare("INSERT INTO comments (post_id, content) VALUES (:post_id, :content)");
        $stmt->bindValue(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->bindValue(':content', $comment, PDO::PARAM_STR);
        $stmt->execute();
    }

    // Перенаправляем обратно на страницу поста
    header('Location: index.php');
    exit;
}
