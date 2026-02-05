<?php
require __DIR__ . "/includes/db.php";
require __DIR__ . "/includes/functions.php";
session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        $errors[] = "All fields are required.";
    }

    if ($password !== $password2) {
        $errors[] = "Passwords do not match.";
    }

    if (!$errors) {
        $check = $pdo->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
        $check->execute([$email]);

        if ($check->fetch()) {
            $errors[] = "Email already exists.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, 'author')");
            $stmt->execute([$name, $email, $hash]);

            header("Location: signin.php");
            exit;
        }
    }
}

$page_title = "Signup";
require __DIR__ . "/includes/header.php";
?>

<section class="form__section">
    <div class="container form__section-container">
        <h2>Sign Up</h2>

        <?php if ($errors): ?>
            <div class="alert__message error">
                <?php foreach ($errors as $e): ?>
                    <p><?= e($e) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Create Password" required>
            <input type="password" name="password2" placeholder="Confirm Password" required>
            <button type="submit" class="btn">Sign Up</button>
            <small>Already have an account? <a href="signin.php">Sign In</a></small>
        </form>
    </div>
</section>

<?php require __DIR__ . "/includes/footer.php"; ?>
