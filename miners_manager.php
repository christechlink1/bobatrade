<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\miners_manager.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

// Fetch all miners
$stmt = $db->prepare("SELECT id, miner_name, status, created_at FROM miners ORDER BY created_at DESC");
$stmt->execute();
$miners = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miners Manager</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Miners Manager</h2>
        <a href="dashboard_admin.php" class="btn btn-primary mb-3">Back to Dashboard</a>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Miner Name</th>
                    <th>Status</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($miners as $miner): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($miner['id']); ?></td>
                        <td><?php echo htmlspecialchars($miner['miner_name']); ?></td>
                        <td><?php echo htmlspecialchars($miner['status']); ?></td>
                        <td><?php echo htmlspecialchars($miner['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>