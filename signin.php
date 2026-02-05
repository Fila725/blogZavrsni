<?php
require __DIR__ . "/includes/db.php";
require __DIR__ . "/includes/functions.php";
session_start();

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user'] = [
            "id" => $user['id'],
            "name" => $user['name'],
            "email" => $user['email'],
            "role" => $user['role']
        ];

        header("Location: admin/dashboard.php");
        exit;
    } else {
        $error = "Invalid login details.";
    }
}

$page_title = "Signin";
require __DIR__ . "/includes/header.php";
?>

<section class="form__section">
    <div class="container form__section-container">
        <h2>Sign In</h2>

        <?php if ($error): ?>
            <div class="alert__message error">
                <p><?= e($error) ?></p>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn">Sign In</button>
            <small>Don't have an account? <a href="signup.php">Sign Up</a></small>
        </form>
    </div>
</section>

<?php require __DIR__ . "/includes/footer.php"; ?>
