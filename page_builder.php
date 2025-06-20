<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\page_builder.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

// Fetch all pages from the database
$stmt = $db->prepare("SELECT id, title, slug, status, created_at FROM pages ORDER BY created_at DESC");
$stmt->execute();
$pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Builder</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Page Builder</h2>
        <a href="dashboard_admin.php" class="btn btn-primary mb-3">Back to Dashboard</a>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addPageModal">Add Page</button>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pages as $page): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($page['id']); ?></td>
                        <td><?php echo htmlspecialchars($page['title']); ?></td>
                        <td><?php echo htmlspecialchars($page['slug']); ?></td>
                        <td><?php echo htmlspecialchars($page['status']); ?></td>
                        <td><?php echo htmlspecialchars($page['created_at']); ?></td>
                        <td>
                            <a href="edit_page.php?id=<?php echo $page['id']; ?>" class="btn btn-info">Edit</a>
                            <a href="delete_page.php?id=<?php echo $page['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this page?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Add Page Modal -->
        <div class="modal fade" id="addPageModal" tabindex="-1" aria-labelledby="addPageModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPageModalLabel">Add Page</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="add_page.php">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="title" class="form-label">Page Title</label>
                                <input type="text" class="form-control" name="title" id="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" class="form-control" name="slug" id="slug" required>
                            </div>
                            <div class="mb-3">
                                <label for="content" class="form-label">Content</label>
                                <textarea class="form-control" name="content" id="content" rows="5" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" name="status" id="status" required>
                                    <option value="published">Published</option>
                                    <option value="unpublished">Unpublished</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Add Page</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>