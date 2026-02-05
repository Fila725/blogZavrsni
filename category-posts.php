<?php
require __DIR__ . "/includes/db.php";
require __DIR__ . "/includes/functions.php";

$slug = $_GET['slug'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM categories WHERE slug=? LIMIT 1");
$stmt->execute([$slug]);
$category = $stmt->fetch();

if (!$category) {
    http_response_code(404);
    exit("Category not found");
}

$page_title = $category['name'];
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

$stmt = $pdo->prepare("
    SELECT p.id, p.title, p.slug, p.excerpt, p.featured_image, p.created_at,
           u.name AS author
    FROM posts p
    JOIN users u ON u.id = p.user_id
    WHERE p.category_id = ? AND p.status='published'
    ORDER BY p.created_at DESC
");
$stmt->execute([$category['id']]);
$posts = $stmt->fetchAll();
?>

<header class="category__title">
    <h2><?= e($category['name']) ?></h2>
</header>

<section class="posts">
    <div class="container posts__container">

        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <article class="post">
                    <div class="post__thumbnail">
                        <?php $imgSrc = post_thumb_src($post['featured_image'] ?? null, "./images/thumbnail1.jpg"); ?>
                        <img src="<?= e($imgSrc) ?>" alt="">
                    </div>

                    <div class="post__info">
                        <h3 class="post__title">
                            <a href="post.php?slug=<?= e($post['slug']) ?>">
                                <?= e($post['title']) ?>
                            </a>
                        </h3>

                        <p class="post__body">
                            <?= e($post['excerpt'] ?? '') ?>
                        </p>

                        <div class="post__author">
                            <div class="post__author-avatar">
                                <img src="./images/avatar1.jpg" alt="">
                            </div>
                            <div class="post__author-info">
                                <h5>By: <?= e($post['author']) ?></h5>
                                <small><?= date("M d, Y - H:i", strtotime($post['created_at'])) ?></small>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="padding: 2rem 0;">No posts in this category.</p>
        <?php endif; ?>

    </div>
</section>

<?php require __DIR__ . "/includes/footer.php"; ?>
