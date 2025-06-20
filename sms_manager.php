<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\sms_manager.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

// Fetch current SMS settings
$stmt = $db->prepare("SELECT * FROM sms_settings");
$stmt->execute();
$smsSettings = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sms_api_url = trim($_POST['sms_api_url']);
    $sms_api_key = trim($_POST['sms_api_key']);
    $sender_id = trim($_POST['sender_id']);

    if (!empty($sms_api_url) && !empty($sms_api_key) && !empty($sender_id)) {
        $stmt = $db->prepare("UPDATE sms_settings SET sms_api_url = ?, sms_api_key = ?, sender_id = ?");
        $stmt->execute([$sms_api_url, $sms_api_key, $sender_id]);

        header("Location: sms_manager.php?message=SMS+settings+updated+successfully");
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
    <title>SMS Manager</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>SMS Manager</h2>
        <a href="dashboard_admin.php" class="btn btn-primary mb-3">Back to Dashboard</a>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_GET['message']); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="sms_api_url" class="form-label">SMS API URL</label>
                <input type="text" class="form-control" name="sms_api_url" id="sms_api_url" value="<?php echo htmlspecialchars($smsSettings['sms_api_url'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="sms_api_key" class="form-label">SMS API Key</label>
                <input type="text" class="form-control" name="sms_api_key" id="sms_api_key" value="<?php echo htmlspecialchars($smsSettings['sms_api_key'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="sender_id" class="form-label">Sender ID</label>
                <input type="text" class="form-control" name="sender_id" id="sender_id" value="<?php echo htmlspecialchars($smsSettings['sender_id'] ?? ''); ?>" required>
            </div>
            <button type="submit" class="btn btn-success">Save Settings</button>
        </form>

        <hr>

        <h3>Send Test SMS</h3>
        <form method="POST" action="send_test_sms.php">
            <div class="mb-3">
                <label for="test_phone" class="form-label">Phone Number</label>
                <input type="text" class="form-control" name="test_phone" id="test_phone" placeholder="Enter phone number" required>
            </div>
            <div class="mb-3">
                <label for="test_message" class="form-label">Message</label>
                <textarea class="form-control" name="test_message" id="test_message" rows="3" placeholder="Enter your test message" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Send Test SMS</button>
        </form>
    </div>
</body>
</html>