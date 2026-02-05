<?php
require __DIR__ . "/includes/db.php";
require __DIR__ . "/includes/functions.php";

$slug = $_GET['slug'] ?? '';

$stmt = $pdo->prepare("
    SELECT p.id, p.title, p.slug, p.content, p.featured_image, p.created_at,
           c.name AS category, c.slug AS category_slug,
           u.name AS author
    FROM posts p
    JOIN categories c ON c.id = p.category_id
    JOIN users u ON u.id = p.user_id
    WHERE p.slug = ? AND p.status='published'
    LIMIT 1
");
$stmt->execute([$slug]);
$post = $stmt->fetch();

if (!$post) {
    http_response_code(404);
    exit("Post not found");
}

$page_title = $post['title'];
require __DIR__ . "/includes/header.php";

/**
 * Helper for thumbnail URL (PUBLIC URL)
 */
function post_thumb_src(?string $filename, string $fallback): string {
    $filename = trim((string)$filename);
    if ($filename !== "") {
        return "/blog/uploads/thumbnails/" . rawurlencode($filename);
    }
    return $fallback;
}

$imgSrc = post_thumb_src($post['featured_image'] ?? null, "./images/featured1.jpg");
?>

<section class="singlepost">
    <div class="container singlepost__container">
        <h2><?= e($post['title']) ?></h2>

        <div class="post__author">
            <div class="post__author-avatar">
                <img src="./images/avatar1.jpg" alt="">
            </div>
            <div class="post__author-info">
                <h5>By: <?= e($post['author']) ?></h5>
                <small><?= date("M d, Y - H:i", strtotime($post['created_at'])) ?></small>
            </div>
        </div>

        <div class="singlepost__thumbnail">
            <img src="<?= e($imgSrc) ?>" alt="">
        </div>

        <p><?= nl2br(e($post['content'])) ?></p>
    </div>
</section>

<?php require __DIR__ . "/includes/footer.php"; ?>
