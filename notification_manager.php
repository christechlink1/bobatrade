<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\notification_manager.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

// Fetch all notifications
$stmt = $db->prepare("SELECT id, title, message, created_at FROM notifications ORDER BY created_at DESC");
$stmt->execute();
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $message = trim($_POST['message']);

    if (!empty($title) && !empty($message)) {
        $stmt = $db->prepare("INSERT INTO notifications (title, message) VALUES (?, ?)");
        $stmt->execute([$title, $message]);

        header("Location: notification_manager.php?message=Notification+sent+successfully");
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
    <title>Notification Manager</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Notification Manager</h2>
        <a href="dashboard_admin.php" class="btn btn-primary mb-3">Back to Dashboard</a>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_GET['message']); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Notification Title</label>
                <input type="text" class="form-control" name="title" id="title" required>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Notification Message</label>
                <textarea class="form-control" name="message" id="message" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-success">Send Notification</button>
        </form>

        <hr>

        <h3>Sent Notifications</h3>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Message</th>
                    <th>Sent At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notifications as $notification): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($notification['id']); ?></td>
                        <td><?php echo htmlspecialchars($notification['title']); ?></td>
                        <td><?php echo htmlspecialchars($notification['message']); ?></td>
                        <td><?php echo htmlspecialchars($notification['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>