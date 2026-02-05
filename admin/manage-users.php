<?php
require __DIR__ . "/../includes/db.php";
require __DIR__ . "/../includes/functions.php";
require __DIR__ . "/../includes/auth.php";

require_login();

if (!is_admin()) {
    exit("Access denied. Admin only.");
}

$page_title = "Manage Users";
require __DIR__ . "/../includes/header.php";

$users = $pdo->query("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC")->fetchAll();
?>

<section class="dashboard">
    <div class="container dashboard__container">
        <aside>
            <ul>
                <li><a href="add-post.php">Add Post</a></li>
                <li><a href="dashboard.php">Manage Posts</a></li>
                <li><a href="add-category.php">Add Category</a></li>
                <li><a href="manage-categories.php">Manage Categories</a></li>
                <li><a href="add-user.php" class="active">Add User</a></li>
                <li><a href="manage-users.php">Manage Users</a></li>
            </ul>
        </aside>

        <main>
            <h2>Manage Users</h2>

            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Date</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= e($user['name']) ?></td>
                            <td><?= e($user['email']) ?></td>
                            <td><?= e($user['role']) ?></td>
                            <td><?= date("M d, Y", strtotime($user['created_at'])) ?></td>

                            <td>
                                <a href="edit-user.php?id=<?= $user['id'] ?>" class="btn sm">Edit</a>
                            </td>

                            <td>
                                <?php if ($_SESSION['user']['id'] != $user['id']): ?>
                                    <a href="delete-user.php?id=<?= $user['id'] ?>" class="btn sm danger">Delete</a>
                                <?php else: ?>
                                    <span style="color: gray;">(You)</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</section>

<?php require __DIR__ . "/../includes/footer.php"; ?>
