<?php
require __DIR__ . "/../includes/db.php";
require __DIR__ . "/../includes/functions.php";
require __DIR__ . "/../includes/auth.php";

require_login();

if (!is_admin()) {
    exit("Access denied. Admin only.");
}

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM categories WHERE id=? LIMIT 1");
$stmt->execute([$id]);
$category = $stmt->fetch();

if (!$category) {
    exit("Category not found.");
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');

    if ($name === '') {
        $errors[] = "Category name is required.";
    }

    if (!$errors) {
        $slug = slugify($name);

        $check = $pdo->prepare("SELECT id FROM categories WHERE slug=? AND id != ? LIMIT 1");
        $check->execute([$slug, $id]);

        if ($check->fetch()) {
            $errors[] = "Another category with this name already exists.";
        } else {
            $update = $pdo->prepare("UPDATE categories SET name=?, slug=? WHERE id=?");
            $update->execute([$name, $slug, $id]);

            header("Location: manage-categories.php");
            exit;
        }
    }
}

$page_title = "Edit Category";
require __DIR__ . "/../includes/header.php";
?>

<section class="form__section">
    <div class="container form__section-container">
        <h2>Edit Category</h2>

        <?php if ($errors): ?>
            <div class="alert__message error">
                <?php foreach ($errors as $e): ?>
                    <p><?= e($e) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="name" value="<?= e($category['name']) ?>" required>
            <button type="submit" class="btn">Update Category</button>
        </form>
    </div>
</section>

<?php require __DIR__ . "/../includes/footer.php"; ?>
