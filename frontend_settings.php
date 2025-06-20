<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\frontend_settings.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

// Fetch current frontend settings
$stmt = $db->prepare("SELECT * FROM frontend_settings");
$stmt->execute();
$frontendSettings = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $homepage_title = trim($_POST['homepage_title']);
    $homepage_banner = trim($_POST['homepage_banner']);
    $footer_text = trim($_POST['footer_text']);

    if (!empty($homepage_title) && !empty($homepage_banner)) {
        $stmt = $db->prepare("UPDATE frontend_settings SET homepage_title = ?, homepage_banner = ?, footer_text = ?");
        $stmt->execute([$homepage_title, $homepage_banner, $footer_text]);

        header("Location: frontend_settings.php?message=Frontend+settings+updated+successfully");
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
    <title>Frontend Settings</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Frontend Settings</h2>
        <a href="dashboard_admin.php" class="btn btn-primary mb-3">Back to Dashboard</a>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_GET['message']); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="homepage_title" class="form-label">Homepage Title</label>
                <input type="text" class="form-control" name="homepage_title" id="homepage_title" value="<?php echo htmlspecialchars($frontendSettings['homepage_title'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="homepage_banner" class="form-label">Homepage Banner URL</label>
                <input type="text" class="form-control" name="homepage_banner" id="homepage_banner" value="<?php echo htmlspecialchars($frontendSettings['homepage_banner'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="footer_text" class="form-label">Footer Text</label>
                <textarea class="form-control" name="footer_text" id="footer_text" rows="3"><?php echo htmlspecialchars($frontendSettings['footer_text'] ?? ''); ?></textarea>
            </div>
            <button type="submit" class="btn btn-success">Save Settings</button>
        </form>
    </div>
</body>
</html>