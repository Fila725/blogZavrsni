<?php
require __DIR__ . "/../includes/db.php";
require __DIR__ . "/../includes/auth.php";

require_login();

if (!is_admin()) {
    exit("Access denied. Admin only.");
}

$id = (int)($_GET['id'] ?? 0);

// prevent admin deleting himself
if ($_SESSION['user']['id'] == $id) {
    exit("You cannot delete your own account.");
}

$stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
$stmt->execute([$id]);

header("Location: manage-users.php");
exit;
