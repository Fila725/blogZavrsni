<?php
require __DIR__ . "/../includes/db.php";
require __DIR__ . "/../includes/auth.php";

require_login();

$id = (int)($_GET['id'] ?? 0);

// get thumbnail
$stmt = $pdo->prepare("SELECT featured_image FROM posts WHERE id=? LIMIT 1");
$stmt->execute([$id]);
$post = $stmt->fetch();

if ($post && !empty($post['featured_image'])) {
    $path = __DIR__ . "/../uploads/thumbnails/" . $post['featured_image'];
    if (file_exists($path)) {
        unlink($path);
    }
}

// delete post
$stmt = $pdo->prepare("DELETE FROM posts WHERE id=?");
$stmt->execute([$id]);

header("Location: dashboard.php");
exit;
