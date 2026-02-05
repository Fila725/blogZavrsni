<?php
require __DIR__ . "/../includes/db.php";
require __DIR__ . "/../includes/auth.php";

require_login();

if (!is_admin()) {
    exit("Access denied. Admin only.");
}

$id = (int)($_GET['id'] ?? 0);

try {
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id=?");
    $stmt->execute([$id]);

    header("Location: manage-categories.php");
    exit;
} catch (PDOException $e) {
    exit("Cannot delete category because posts are using it.");
}
