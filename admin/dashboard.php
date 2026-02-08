<?php
require __DIR__ . "/../includes/db.php";
require __DIR__ . "/../includes/functions.php";
require __DIR__ . "/../includes/auth.php";

require_login();

$user_id = (int)($_SESSION['user']['id'] ?? 0);

/**
 * ------------------------------------------------------------
 * AJAX (Fetch) handlers
 * ------------------------------------------------------------
 */
if (isset($_GET['ajax']) && $_GET['ajax'] === 'get_post') {
    header('Content-Type: application/json; charset=utf-8');

    $id = (int)($_GET['id'] ?? 0);
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Invalid post id.']);
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT id, title, excerpt, content
        FROM posts
        WHERE id = ? AND user_id = ?
        LIMIT 1
    ");
    $stmt->execute([$id, $user_id]);
    $post = $stmt->fetch();

    if (!$post) {
        http_response_code(404);
        echo json_encode(['ok' => false, 'error' => 'Post not found.']);
        exit;
    }

    echo json_encode(['ok' => true, 'post' => $post]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Support JSON fetch() body
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);

    // If not JSON, fallback to normal POST (just in case)
    if (!is_array($data)) {
        $data = $_POST;
    }

    if (($data['ajax'] ?? '') === 'update_post') {
        header('Content-Type: application/json; charset=utf-8');

        $id = (int)($data['id'] ?? 0);
        $title = trim((string)($data['title'] ?? ''));
        $excerpt = trim((string)($data['excerpt'] ?? ''));
        $content = trim((string)($data['content'] ?? ''));

        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Invalid post id.']);
            exit;
        }

        if ($title === '' || $content === '') {
            http_response_code(422);
            echo json_encode(['ok' => false, 'error' => 'Title and body are required.']);
            exit;
        }

        // Ensure the post belongs to the logged-in user
        $check = $pdo->prepare("SELECT id FROM posts WHERE id = ? AND user_id = ? LIMIT 1");
        $check->execute([$id, $user_id]);
        if (!$check->fetch()) {
            http_response_code(403);
            echo json_encode(['ok' => false, 'error' => 'Not allowed.']);
            exit;
        }

        $slug = slugify($title);

        $update = $pdo->prepare("
            UPDATE posts
            SET title = ?, slug = ?, excerpt = ?, content = ?
            WHERE id = ? AND user_id = ?
        ");
        $update->execute([$title, $slug, $excerpt, $content, $id, $user_id]);

        echo json_encode([
            'ok' => true,
            'post' => [
                'id' => $id,
                'title' => $title,
                'excerpt' => $excerpt,
                'content' => $content
            ]
        ]);
        exit;
    }
}

$page_title = "Dashboard";
require __DIR__ . "/../includes/header.php";

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
                            <td class="js-post-title"><?= e($post['title']) ?></td>
                            <td><?= e($post['category']) ?></td>
                            <td><?= date("M d, Y", strtotime($post['created_at'])) ?></td>
                            <td>
                                <button
                                    type="button"
                                    class="btn sm js-edit-post"
                                    data-post-id="<?= (int)$post['id'] ?>"
                                >
                                    Edit
                                </button>
                            </td>
                            <td><a href="delete-post.php?id=<?= (int)$post['id'] ?>" class="btn sm danger">Delete</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Modal (popup) -->
            <style>
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.75);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 1rem;
    }

    .modal {
        background: #1e1e2f;
        color: #ffffff;
        max-width: 720px;
        width: 100%;
        border-radius: 12px;
        padding: 1.5rem;
        max-height: 90vh;
        overflow: auto;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .modal__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .modal__title {
        font-size: 1.3rem;
        font-weight: 600;
        margin: 0;
        color: #ffffff;
    }

    .modal__close {
        background: transparent;
        border: 0;
        font-size: 1.8rem;
        cursor: pointer;
        line-height: 1;
        color: #ffffff;
        transition: 0.2s;
    }

    .modal__close:hover {
        color: #ff4d4d;
        transform: scale(1.1);
    }

    .modal label {
        display: block;
        margin: 0.85rem 0 0.35rem;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.9);
    }

    .modal input,
    .modal textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 10px;
        background: rgba(0, 0, 0, 0.25);
        color: #ffffff;
        outline: none;
        font-size: 0.95rem;
        transition: 0.2s;
    }

    .modal input:focus,
    .modal textarea:focus {
        border-color: #6c63ff;
        box-shadow: 0 0 0 2px rgba(108, 99, 255, 0.35);
    }

    .modal textarea {
        resize: vertical;
        min-height: 120px;
    }

    .modal__actions {
        display: flex;
        gap: 0.7rem;
        justify-content: flex-end;
        margin-top: 1.2rem;
    }

    .modal__error {
        display: none;
        margin: 1rem 0;
        padding: 0.9rem;
        border-radius: 10px;
        background: rgba(255, 77, 77, 0.15);
        color: #ff9a9a;
        border: 1px solid rgba(255, 77, 77, 0.35);
        font-weight: 500;
    }
</style>


            <div class="modal-overlay" id="editPostOverlay" aria-hidden="true">
                <div class="modal" role="dialog" aria-modal="true" aria-labelledby="editPostTitle">
                    <div class="modal__header">
                        <h3 class="modal__title" id="editPostTitle">Edit Post</h3>
                        <button type="button" class="modal__close" id="editPostCloseBtn" aria-label="Close">&times;</button>
                    </div>

                    <div class="modal__error" id="editPostError"></div>

                    <form id="editPostForm">
                        <input type="hidden" id="editPostId" name="id" value="">

                        <label for="editPostTitleInput">Title</label>
                        <input type="text" id="editPostTitleInput" name="title" required>

                        <label for="editPostExcerpt">Excerpt</label>
                        <textarea id="editPostExcerpt" name="excerpt" rows="4"></textarea>

                        <label for="editPostContent">Body</label>
                        <textarea id="editPostContent" name="content" rows="10" required></textarea>

                        <div class="modal__actions">
                            <button type="button" class="btn sm" id="editPostCancelBtn">Cancel</button>
                            <button type="submit" class="btn sm">Save</button>
                        </div>
                    </form>
                </div>
            </div>

        </main>
    </div>
</section>

<?php require __DIR__ . "/../includes/footer.php"; ?>
