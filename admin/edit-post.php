<?php
require __DIR__ . "/../includes/db.php";
require __DIR__ . "/../includes/functions.php";
require __DIR__ . "/../includes/auth.php";

require_login();

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=? LIMIT 1");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    exit("Post not found.");
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $status = ($_POST['status'] ?? 'published') === 'draft' ? 'draft' : 'published';

    if ($title === '' || $content === '' || $category_id <= 0) {
        $errors[] = "Title, content and category are required.";
    }

    if (!$errors) {
        $slug = slugify($title);

        // IMPORTANT: featured_image is NOT updated here
        $update = $pdo->prepare("
            UPDATE posts
            SET title=?, slug=?, excerpt=?, content=?, category_id=?, status=?
            WHERE id=?
        ");

        $update->execute([
            $title,
            $slug,
            $excerpt,
            $content,
            $category_id,
            $status,
            $id
        ]);

        header("Location: dashboard.php");
        exit;
    }
}

$categories = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();

$page_title = "Edit Post";
require __DIR__ . "/../includes/header.php";
?>

<section class="form__section">
    <div class="container form__section-container">
        <h2>Edit Post</h2>

        <?php if ($errors): ?>
            <div class="alert__message error">
                <?php foreach ($errors as $e): ?>
                    <p><?= e($e) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- No enctype needed since we are NOT uploading files anymore -->
        <form method="POST">
            <input type="text" name="title" value="<?= e($post['title']) ?>" required>

            <textarea name="excerpt" rows="4"><?= e($post['excerpt']) ?></textarea>

            <textarea name="content" rows="10" required><?= e($post['content']) ?></textarea>

            <select name="category_id" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $post['category_id'] ? 'selected' : '' ?>>
                        <?= e($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="status">
                <option value="published" <?= $post['status'] === 'published' ? 'selected' : '' ?>>Published</option>
                <option value="draft" <?= $post['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
            </select>

            <p><strong>Current Thumbnail:</strong></p>
            <?php if (!empty($post['featured_image'])): ?>
                <img src="/blog/uploads/thumbnails/<?= e($post['featured_image']) ?>" style="max-width:150px;height:auto;" alt="">
            <?php else: ?>
                <p>No thumbnail uploaded.</p>
            <?php endif; ?>

            <!-- Thumbnail upload REMOVED -->
            <button type="submit" class="btn">Update Post</button>
        </form>
    </div>
</section>

<?php require __DIR__ . "/../includes/footer.php"; ?>
