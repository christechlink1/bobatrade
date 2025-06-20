<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\user_login_history.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

// Fetch user login history from the database
$stmt = $db->prepare("SELECT id, user_id, ip_address, device_info, login_time FROM user_login_history ORDER BY login_time DESC");
$stmt->execute();
$loginHistory = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login History</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>User Login History</h2>
        <a href="dashboard_admin.php" class="btn btn-primary mb-3">Back to Dashboard</a>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>IP Address</th>
                    <th>Device Info</th>
                    <th>Login Time</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($loginHistory as $entry): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($entry['id']); ?></td>
                        <td><?php echo htmlspecialchars($entry['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($entry['ip_address']); ?></td>
                        <td><?php echo htmlspecialchars($entry['device_info']); ?></td>
                        <td><?php echo htmlspecialchars($entry['login_time']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>