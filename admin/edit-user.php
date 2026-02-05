<?php
require __DIR__ . "/../includes/db.php";
require __DIR__ . "/../includes/functions.php";
require __DIR__ . "/../includes/auth.php";

require_login();

if (!is_admin()) {
    exit("Access denied. Admin only.");
}

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM users WHERE id=? LIMIT 1");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    exit("User not found.");
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = ($_POST['role'] ?? 'author') === 'admin' ? 'admin' : 'author';
    $new_password = $_POST['password'] ?? '';

    if ($name === '' || $email === '') {
        $errors[] = "Name and email are required.";
    }

    if (!$errors) {
        // if password entered, update password
        if ($new_password !== '') {
            $hash = password_hash($new_password, PASSWORD_DEFAULT);

            $update = $pdo->prepare("UPDATE users SET name=?, email=?, role=?, password_hash=? WHERE id=?");
            $update->execute([$name, $email, $role, $hash, $id]);
        } else {
            $update = $pdo->prepare("UPDATE users SET name=?, email=?, role=? WHERE id=?");
            $update->execute([$name, $email, $role, $id]);
        }

        header("Location: manage-users.php");
        exit;
    }
}

$page_title = "Edit User";
require __DIR__ . "/../includes/header.php";
?>

<section class="form__section">
    <div class="container form__section-container">
        <h2>Edit User</h2>

        <?php if ($errors): ?>
            <div class="alert__message error">
                <?php foreach ($errors as $e): ?>
                    <p><?= e($e) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="name" value="<?= e($user['name']) ?>" required>
            <input type="email" name="email" value="<?= e($user['email']) ?>" required>

            <select name="role">
                <option value="author" <?= $user['role'] === 'author' ? 'selected' : '' ?>>Author</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>

            <input type="password" name="password" placeholder="New password (leave empty to keep old)">

            <button type="submit" class="btn">Update User</button>
        </form>
    </div>
</section>

<?php require __DIR__ . "/../includes/footer.php"; ?>
