<?php
include 'config/config.php';

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = 5; $offset = ($page - 1) * $limit;

$stmt = $pdo->prepare("SELECT * FROM posts ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$posts = $stmt->fetchAll();

foreach ($posts as $post): ?>
    <div class="container">
        <h2 class="container_header subheader"><?= secure_input($post['header']) ?></h2>
        <p class="container_content"><?= nl2br(secure_input($post['content'])) ?></p>
        <?php if (!empty($post['image'])): ?>
            <img src="<?= secure_input($post['image']) ?>" alt="Изображение поста" class="container_image">
        <?php endif; ?>
    </div>
<?php endforeach; ?>
