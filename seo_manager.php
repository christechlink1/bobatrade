<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\seo_manager.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

// Fetch current SEO settings
$stmt = $db->prepare("SELECT * FROM seo_settings");
$stmt->execute();
$seoSettings = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $meta_title = trim($_POST['meta_title']);
    $meta_description = trim($_POST['meta_description']);
    $meta_keywords = trim($_POST['meta_keywords']);

    if (!empty($meta_title) && !empty($meta_description)) {
        $stmt = $db->prepare("UPDATE seo_settings SET meta_title = ?, meta_description = ?, meta_keywords = ?");
        $stmt->execute([$meta_title, $meta_description, $meta_keywords]);

        header("Location: seo_manager.php?message=SEO+settings+updated+successfully");
        exit();
    } else {
        $error = "Invalid input. Please check your data.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEO Manager</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>SEO Manager</h2>
        <a href="dashboard_admin.php" class="btn btn-primary mb-3">Back to Dashboard</a>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_GET['message']); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="meta_title" class="form-label">Meta Title</label>
                <input type="text" class="form-control" name="meta_title" id="meta_title" value="<?php echo htmlspecialchars($seoSettings['meta_title'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="meta_description" class="form-label">Meta Description</label>
                <textarea class="form-control" name="meta_description" id="meta_description" rows="3" required><?php echo htmlspecialchars($seoSettings['meta_description'] ?? ''); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="meta_keywords" class="form-label">Meta Keywords</label>
                <textarea class="form-control" name="meta_keywords" id="meta_keywords" rows="3"><?php echo htmlspecialchars($seoSettings['meta_keywords'] ?? ''); ?></textarea>
            </div>
            <button type="submit" class="btn btn-success">Save Settings</button>
        </form>
    </div>
</body>
</html>