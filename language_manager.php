<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\language_manager.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

// Fetch all languages from the database
$stmt = $db->prepare("SELECT id, name, code, status FROM languages ORDER BY name ASC");
$stmt->execute();
$languages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Language Manager</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Language Manager</h2>
        <a href="dashboard_admin.php" class="btn btn-primary mb-3">Back to Dashboard</a>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addLanguageModal">Add Language</button>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Code</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($languages as $language): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($language['id']); ?></td>
                        <td><?php echo htmlspecialchars($language['name']); ?></td>
                        <td><?php echo htmlspecialchars($language['code']); ?></td>
                        <td><?php echo htmlspecialchars($language['status']); ?></td>
                        <td>
                            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#editLanguageModal<?php echo $language['id']; ?>">Edit</button>
                            <a href="delete_language.php?id=<?php echo $language['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this language?');">Delete</a>
                        </td>
                    </tr>

                    <!-- Edit Language Modal -->
                    <div class="modal fade" id="editLanguageModal<?php echo $language['id']; ?>" tabindex="-1" aria-labelledby="editLanguageModalLabel<?php echo $language['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editLanguageModalLabel<?php echo $language['id']; ?>">Edit Language</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" action="edit_language.php">
                                    <div class="modal-body">
                                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($language['id']); ?>">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control" name="name" id="name" value="<?php echo htmlspecialchars($language['name']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="code" class="form-label">Code</label>
                                            <input type="text" class="form-control" name="code" id="code" value="<?php echo htmlspecialchars($language['code']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-select" name="status" id="status" required>
                                                <option value="active" <?php echo $language['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                                                <option value="inactive" <?php echo $language['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Add Language Modal -->
        <div class="modal fade" id="addLanguageModal" tabindex="-1" aria-labelledby="addLanguageModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addLanguageModalLabel">Add Language</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="add_language.php">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" id="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="code" class="form-label">Code</label>
                                <input type="text" class="form-control" name="code" id="code" required>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" name="status" id="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Add Language</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>