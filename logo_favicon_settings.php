<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\logo_favicon_settings.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

// Fetch current logo and favicon settings
$stmt = $db->prepare("SELECT * FROM logo_favicon_settings");
$stmt->execute();
$settings = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $logo = $_FILES['logo'];
    $favicon = $_FILES['favicon'];

    $uploadDir = "../uploads/";
    $logoPath = $uploadDir . basename($logo['name']);
    $faviconPath = $uploadDir . basename($favicon['name']);

    if (move_uploaded_file($logo['tmp_name'], $logoPath) && move_uploaded_file($favicon['tmp_name'], $faviconPath)) {
        $stmt = $db->prepare("UPDATE logo_favicon_settings SET logo = ?, favicon = ?");
        $stmt->execute([$logoPath, $faviconPath]);

        header("Location: logo_favicon_settings.php?message=Settings+updated+successfully");
        exit();
    } else {
        $error = "Failed to upload files. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logo & Favicon Settings</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Logo & Favicon Settings</h2>
        <a href="dashboard_admin.php" class="btn btn-primary mb-3">Back to Dashboard</a>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_GET['message']); ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="logo" class="form-label">Upload Logo</label>
                <input type="file" class="form-control" name="logo" id="logo" required>
                <?php if (!empty($settings['logo'])): ?>
                    <img src="<?php echo htmlspecialchars($settings['logo']); ?>" alt="Logo" class="mt-2" style="max-width: 150px;">
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="favicon" class="form-label">Upload Favicon</label>
                <input type="file" class="form-control" name="favicon" id="favicon" required>
                <?php if (!empty($settings['favicon'])): ?>
                    <img src="<?php echo htmlspecialchars($settings['favicon']); ?>" alt="Favicon" class="mt-2" style="max-width: 50px;">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-success">Save Settings</button>
        </form>
    </div>
</body>
</html>