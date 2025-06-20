<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\basic_settings.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

// Fetch current settings
$stmt = $db->prepare("SELECT * FROM settings");
$stmt->execute();
$settings = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site_name = trim($_POST['site_name']);
    $contact_email = trim($_POST['contact_email']);
    $footer_text = trim($_POST['footer_text']);

    if (!empty($site_name) && filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $db->prepare("UPDATE settings SET site_name = ?, contact_email = ?, footer_text = ?");
        $stmt->execute([$site_name, $contact_email, $footer_text]);

        header("Location: basic_settings.php?message=Settings+updated+successfully");
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
    <title>Basic Settings</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Basic Settings</h2>
        <a href="dashboard_admin.php" class="btn btn-primary mb-3">Back to Dashboard</a>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_GET['message']); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="site_name" class="form-label">Site Name</label>
                <input type="text" class="form-control" name="site_name" id="site_name" value="<?php echo htmlspecialchars($settings['site_name'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="contact_email" class="form-label">Contact Email</label>
                <input type="email" class="form-control" name="contact_email" id="contact_email" value="<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="footer_text" class="form-label">Footer Text</label>
                <textarea class="form-control" name="footer_text" id="footer_text" rows="3"><?php echo htmlspecialchars($settings['footer_text'] ?? ''); ?></textarea>
            </div>
            <button type="submit" class="btn btn-success">Save Settings</button>
        </form>
    </div>
</body>
</html>