<?php
declare(strict_types=1);
if (session_status() === PHP_SESSION_NONE) session_start();

function require_login(): void {
  if (empty($_SESSION['user'])) {
    header("Location: /blog/signin.php");
    exit;
  }
}

function is_admin(): bool {
  return !empty($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'admin';
}
