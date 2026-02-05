<?php
require __DIR__ . "/../includes/db.php";
require __DIR__ . "/../includes/functions.php";
require __DIR__ . "/../includes/auth.php";

require_login();

if (!is_admin()) {
    exit("Access denied. Admin only.");
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');

    if ($name === '') {
        $errors[] = "Category name is required.";
    }

    if (!$errors) {
        $slug = slugify($name);

        $check = $pdo->prepare("SELECT id FROM categories WHERE slug=? LIMIT 1");
        $check->execute([$slug]);

        if ($check->fetch()) {
            $errors[] = "Category already exists.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
            $stmt->execute([$name, $slug]);

            header("Location: manage-categories.php");
            exit;
        }
    }
}

$page_title = "Add Category";
require __DIR__ . "/../includes/header.php";
?>

<section class="form__section">
    <div class="container form__section-container">
        <h2>Add Category</h2>

        <?php if ($errors): ?>
            <div class="alert__message error">
                <?php foreach ($errors as $e): ?>
                    <p><?= e($e) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="name" placeholder="Category Name" required>
            <button type="submit" class="btn">Add Category</button>
        </form>
    </div>
</section>

<?php require __DIR__ . "/../includes/footer.php"; ?>
