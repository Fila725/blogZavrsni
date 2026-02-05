<?php
require __DIR__ . "/../includes/db.php";
require __DIR__ . "/../includes/functions.php";
require __DIR__ . "/../includes/auth.php";

require_login();

if (!is_admin()) {
    exit("Access denied. Admin only.");
}

$page_title = "Manage Categories";
require __DIR__ . "/../includes/header.php";

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
?>

<section class="dashboard">
    <div class="container dashboard__container">
        <aside>
            <ul>
                <li><a href="add-post.php">Add Post</a></li>
                <li><a href="dashboard.php">Manage Posts</a></li>
                <li><a href="add-category.php" class="active">Add Category</a></li>
                <li><a href="manage-categories.php">Manage Categories</a></li>
                <li><a href="add-user.php">Add User</a></li>
                <li><a href="manage-users.php">Manage Users</a></li>
            </ul>
        </aside>

        <main>
            <h2>Manage Categories</h2>

            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td><?= e($cat['name']) ?></td>
                            <td><?= e($cat['slug']) ?></td>
                            <td><a href="edit-category.php?id=<?= $cat['id'] ?>" class="btn sm">Edit</a></td>
                            <td><a href="delete-category.php?id=<?= $cat['id'] ?>" class="btn sm danger">Delete</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</section>

<?php require __DIR__ . "/../includes/footer.php"; ?>
