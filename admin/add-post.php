<?php
require __DIR__ . "/../includes/db.php";
require __DIR__ . "/../includes/functions.php";
require __DIR__ . "/../includes/auth.php";

require_login();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $status = ($_POST['status'] ?? 'published') === 'draft' ? 'draft' : 'published';

    // thumbnail
    $thumbnail = $_FILES['thumbnail'] ?? null;

    if ($title === '' || $content === '' || $category_id <= 0) {
        $errors[] = "Title, content and category are required.";
    }

    if (!$thumbnail || $thumbnail['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Thumbnail image is required.";
    }

    $thumbnail_name = null;

    if (!$errors) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];

        if (!in_array($thumbnail['type'], $allowed_types)) {
            $errors[] = "Thumbnail must be JPG, PNG, or WEBP.";
        }

        if ($thumbnail['size'] > 2 * 1024 * 1024) {
            $errors[] = "Thumbnail must be less than 2MB.";
        }

        if (!$errors) {
            $ext = pathinfo($thumbnail['name'], PATHINFO_EXTENSION);
            $thumbnail_name = time() . "_" . uniqid() . "." . $ext;

            $upload_path = __DIR__ . "/../uploads/thumbnails/" . $thumbnail_name;

            if (!move_uploaded_file($thumbnail['tmp_name'], $upload_path)) {
                $errors[] = "Thumbnail upload failed.";
            }
        }
    }

    if (!$errors) {
        $slug = slugify($title);

        // ensure unique slug
        $base = $slug;
        $i = 2;

        while (true) {
            $check = $pdo->prepare("SELECT id FROM posts WHERE slug=? LIMIT 1");
            $check->execute([$slug]);

            if (!$check->fetch()) break;
            $slug = $base . "-" . $i++;
        }

        $stmt = $pdo->prepare("
            INSERT INTO posts (user_id, category_id, title, slug, excerpt, content, featured_image, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $_SESSION['user']['id'],
            $category_id,
            $title,
            $slug,
            $excerpt,
            $content,
            $thumbnail_name,
            $status
        ]);

        header("Location: dashboard.php");
        exit;
    }
}

$categories = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();

$page_title = "Add Post";
require __DIR__ . "/../includes/header.php";
?>

<section class="form__section">
    <div class="container form__section-container">
        <h2>Add Post</h2>

        <?php if ($errors): ?>
            <div class="alert__message error">
                <?php foreach ($errors as $e): ?>
                    <p><?= e($e) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Title" required>
            <textarea name="excerpt" rows="4" placeholder="Excerpt"></textarea>
            <textarea name="content" rows="10" placeholder="Content" required></textarea>

            <select name="category_id" required>
                <option value="">Select Category</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= e($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>

            <select name="status">
                <option value="published">Published</option>
                <option value="draft">Draft</option>
            </select>

            <input type="file" name="thumbnail" accept="image/*" required>

            <button type="submit" class="btn">Add Post</button>
        </form>
    </div>
</section>

<?php require __DIR__ . "/../includes/footer.php"; ?>
