<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\sales_log.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

// Fetch all sales logs
$stmt = $db->prepare("SELECT id, user_id, plan_id, amount, created_at FROM sales ORDER BY created_at DESC");
$stmt->execute();
$sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Sales Log</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>All Sales Log</h2>
        <a href="dashboard_admin.php" class="btn btn-primary mb-3">Back to Dashboard</a>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Plan ID</th>
                    <th>Amount</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sales as $sale): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($sale['id']); ?></td>
                        <td><?php echo htmlspecialchars($sale['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($sale['plan_id']); ?></td>
                        <td><?php echo htmlspecialchars($sale['amount']); ?></td>
                        <td><?php echo htmlspecialchars($sale['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>