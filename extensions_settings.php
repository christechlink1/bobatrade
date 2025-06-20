<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\extensions_settings.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

// Fetch all extensions from the database
$stmt = $db->prepare("SELECT id, name, description, status FROM extensions ORDER BY name ASC");
$stmt->execute();
$extensions = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $extension_id = (int)$_POST['extension_id'];
    $new_status = trim($_POST['status']);

    if (in_array($new_status, ['enabled', 'disabled'])) {
        $stmt = $db->prepare("UPDATE extensions SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $extension_id]);

        header("Location: extensions_settings.php?message=Extension+status+updated+successfully");
        exit();
    } else {
        $error = "Invalid status value.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extensions Settings</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Extensions Settings</h2>
        <a href="dashboard_admin.php" class="btn btn-primary mb-3">Back to Dashboard</a>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_GET['message']); ?></div>
        <?php endif; ?>

        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($extensions as $extension): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($extension['id']); ?></td>
                        <td><?php echo htmlspecialchars($extension['name']); ?></td>
                        <td><?php echo htmlspecialchars($extension['description']); ?></td>
                        <td><?php echo htmlspecialchars($extension['status']); ?></td>
                        <td>
                            <form method="POST" style="display: inline-block;">
                                <input type="hidden" name="extension_id" value="<?php echo $extension['id']; ?>">
                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="enabled" <?php echo $extension['status'] === 'enabled' ? 'selected' : ''; ?>>Enable</option>
                                    <option value="disabled" <?php echo $extension['status'] === 'disabled' ? 'selected' : ''; ?>>Disable</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>