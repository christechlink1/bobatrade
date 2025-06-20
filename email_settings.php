<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\email_settings.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

// Fetch current email settings
$stmt = $db->prepare("SELECT * FROM email_settings");
$stmt->execute();
$emailSettings = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $smtp_host = trim($_POST['smtp_host']);
    $smtp_port = trim($_POST['smtp_port']);
    $smtp_user = trim($_POST['smtp_user']);
    $smtp_password = trim($_POST['smtp_password']);
    $sender_email = trim($_POST['sender_email']);
    $sender_name = trim($_POST['sender_name']);

    if (!empty($smtp_host) && !empty($smtp_port) && !empty($smtp_user) && !empty($smtp_password) && filter_var($sender_email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $db->prepare("UPDATE email_settings SET smtp_host = ?, smtp_port = ?, smtp_user = ?, smtp_password = ?, sender_email = ?, sender_name = ?");
        $stmt->execute([$smtp_host, $smtp_port, $smtp_user, $smtp_password, $sender_email, $sender_name]);

        header("Location: email_settings.php?message=Email+settings+updated+successfully");
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
    <title>Email Settings</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Email Settings</h2>
        <a href="dashboard_admin.php" class="btn btn-primary mb-3">Back to Dashboard</a>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_GET['message']); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="smtp_host" class="form-label">SMTP Host</label>
                <input type="text" class="form-control" name="smtp_host" id="smtp_host" value="<?php echo htmlspecialchars($emailSettings['smtp_host'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="smtp_port" class="form-label">SMTP Port</label>
                <input type="text" class="form-control" name="smtp_port" id="smtp_port" value="<?php echo htmlspecialchars($emailSettings['smtp_port'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="smtp_user" class="form-label">SMTP User</label>
                <input type="text" class="form-control" name="smtp_user" id="smtp_user" value="<?php echo htmlspecialchars($emailSettings['smtp_user'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="smtp_password" class="form-label">SMTP Password</label>
                <input type="password" class="form-control" name="smtp_password" id="smtp_password" value="<?php echo htmlspecialchars($emailSettings['smtp_password'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="sender_email" class="form-label">Sender Email</label>
                <input type="email" class="form-control" name="sender_email" id="sender_email" value="<?php echo htmlspecialchars($emailSettings['sender_email'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="sender_name" class="form-label">Sender Name</label>
                <input type="text" class="form-control" name="sender_name" id="sender_name" value="<?php echo htmlspecialchars($emailSettings['sender_name'] ?? ''); ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Save Settings</button>
        </form>
    </div>
</body>
</html>