<?php
$page_title = "Blog";
require __DIR__ . "/includes/db.php";
require __DIR__ . "/includes/functions.php";
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

/**
 * Fetch all published posts
 */
$stmt = $pdo->query("
    SELECT p.id, p.title, p.slug, p.excerpt, p.featured_image, p.created_at,
           c.name AS category, c.slug AS category_slug,
           u.name AS author
    FROM posts p
    JOIN categories c ON c.id = p.category_id
    JOIN users u ON u.id = p.user_id
    WHERE p.status='published'
    ORDER BY p.created_at DESC
");

$posts = $stmt->fetchAll();
?>



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
                        <a href="category-posts.php?slug=<?= e($post['category_slug']) ?>" class="category__button">
                            <?= e($post['category']) ?>
                        </a>

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
            <p style="padding: 2rem 0;">No posts found.</p>
        <?php endif; ?>

    </div>
</section>


<section class="category__buttons">
    <div class="container category__buttons-container">
        <?php
        $cats = $pdo->query("SELECT name, slug FROM categories ORDER BY name")->fetchAll();
        foreach ($cats as $cat):
        ?>
            <a href="category-posts.php?slug=<?= e($cat['slug']) ?>" class="category__button">
                <?= e($cat['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<?php require __DIR__ . "/includes/footer.php"; ?>
