<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/functions.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title ?? "FLASH News") ?></title>

    <link rel="stylesheet" href="/blog/style.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.2.0/css/line.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">

    <!-- JS FILE (defer ensures HTML loads first) -->
    <script src="/blog/main.js" defer></script>
</head>
<body>

<nav>
    <div class="container nav__container">
        <a href="/blog/index.php" class="nav__logo">FLASH News</a>

        <ul class="nav__items">
            <li><a href="/blog/blog.php">Blog</a></li>
            <li><a href="/blog/about.php">About</a></li>
            <li><a href="/blog/services.php">Sources</a></li>
            <li><a href="/blog/contact.php">Contact</a></li>

            <?php if (!empty($_SESSION['user'])): ?>
                <li class="nav__profile">
                    <div class="avatar">
                        <img src="/blog/images/avatar1.jpg" alt="Profile Avatar">
                    </div>
                    <ul>
                        <li><a href="/blog/admin/dashboard.php">Dashboard</a></li>
                        <li><a href="/blog/logout.php">Logout</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li><a href="/blog/signin.php">Signin</a></li>
            <?php endif; ?>
        </ul>

        <button id="open__nav-btn" aria-label="Open Menu">
            <i class="uil uil-bars"></i>
        </button>

        <button id="close__nav-btn" aria-label="Close Menu">
            <i class="uil uil-multiply"></i>
        </button>
    </div>
</nav>
