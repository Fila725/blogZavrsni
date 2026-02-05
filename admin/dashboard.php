<?php
require __DIR__ . "/../includes/db.php";
require __DIR__ . "/../includes/functions.php";
require __DIR__ . "/../includes/auth.php";

require_login();

$page_title = "Dashboard";
require __DIR__ . "/../includes/header.php";

$user_id = $_SESSION['user']['id'];

$stmt = $pdo->prepare("
    SELECT p.id, p.title, p.created_at, c.name AS category
    FROM posts p
    JOIN categories c ON c.id = p.category_id
    WHERE p.user_id = ?
    ORDER BY p.created_at DESC
");
$stmt->execute([$user_id]);
$posts = $stmt->fetchAll();
?>

<section class="dashboard">
    <div class="container dashboard__container">
        <aside>
            <ul>
                <li><a href="add-post.php">Add Post</a></li>
                <li><a href="dashboard.php">Manage Posts</a></li>
                <li><a href="add-category.php">Add Category</a></li>
                <li><a href="manage-categories.php">Manage Categories</a></li>

                <?php if (is_admin()): ?>
                    <li><a href="add-user.php">Add User</a></li>
                    <li><a href="manage-users.php">Manage Users</a></li>
                <?php endif; ?>
            </ul>
        </aside>

        <main>
            <h2>Manage Posts</h2>

            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><?= e($post['title']) ?></td>
                            <td><?= e($post['category']) ?></td>
                            <td><?= date("M d, Y", strtotime($post['created_at'])) ?></td>
                            <td><a href="edit-post.php?id=<?= $post['id'] ?>" class="btn sm">Edit</a></td>
                            <td><a href="delete-post.php?id=<?= $post['id'] ?>" class="btn sm danger">Delete</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </main>
    </div>
</section>

<?php require __DIR__ . "/../includes/footer.php"; ?>
