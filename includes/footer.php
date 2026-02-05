<?php
require_once __DIR__ . "/db.php";

// Categories 
$stmt = $pdo->query("SELECT name, slug FROM categories ORDER BY name ASC");
$footer_categories = $stmt->fetchAll();

// Pages 
$footer_pages = [
    ["label" => "Home",     "href" => "/blog/index.php"],
    ["label" => "Blog",     "href" => "/blog/blog.php"],
    ["label" => "About",    "href" => "/blog/about.php"],
    ["label" => "Services", "href" => "/blog/services.php"],
    ["label" => "Contact",  "href" => "/blog/contact.php"],
];
?>

<footer>
    <div class="footer__socials">
        <a href="https://youtube.com/" target="_blank"><i class="uil uil-youtube"></i></a>
        <a href="https://facebook.com/" target="_blank"><i class="uil uil-facebook-f"></i></a>
        <a href="https://instagram.com/" target="_blank"><i class="uil uil-instagram-alt"></i></a>
        <a href="https://linkedin.com/" target="_blank"><i class="uil uil-linkedin"></i></a>
        <a href="https://twitter.com/" target="_blank"><i class="uil uil-twitter"></i></a>
    </div>

    <div class="container footer__container">
        <article>
            <h4>Categories</h4>
            <ul>
                <?php if (!empty($footer_categories)): ?>
                    <?php foreach ($footer_categories as $cat): ?>
                        <li>
                            <a href="/blog/category-posts.php?slug=<?= e($cat['slug']) ?>">
                                <?= e($cat['name']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li><a href="/blog/blog.php">No categories yet</a></li>
                <?php endif; ?>
            </ul>
        </article>

        <article>
            <h4>Pages</h4>  
            <ul>
                <?php foreach ($footer_pages as $p): ?>
                    <li><a href="<?= e($p['href']) ?>"><?= e($p['label']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </article>
    </div>

    <div class="footer__copyright">
        <small>Copyright &copy; <?= date("Y") ?> Blogerspace</small>
    </div>
</footer>
