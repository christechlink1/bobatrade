<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\plan_manager.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

// Fetch all plans
$stmt = $db->prepare("SELECT id, plan_name, price, duration_days AS duration, created_at FROM plans ORDER BY created_at DESC");
$stmt->execute();
$plans = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan Manager</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Plan Manager</h2>
        <a href="dashboard_admin.php" class="btn btn-primary mb-3">Back to Dashboard</a>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Plan Name</th>
                    <th>Price</th>
                    <th>Duration</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($plans as $plan): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($plan['id']); ?></td>
                        <td><?php echo htmlspecialchars($plan['plan_name']); ?></td>
                        <td><?php echo htmlspecialchars($plan['price']); ?></td>
                        <td><?php echo htmlspecialchars($plan['duration']); ?></td>
                        <td><?php echo htmlspecialchars($plan['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>