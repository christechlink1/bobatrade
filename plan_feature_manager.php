<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\plan_feature_manager.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

// Fetch all plan features
$stmt = $db->prepare("SELECT id, plan_id, feature_name, created_at FROM plan_features ORDER BY created_at DESC");
$stmt->execute();
$features = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan Feature Manager</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Plan Feature Manager</h2>
        <a href="dashboard_admin.php" class="btn btn-primary mb-3">Back to Dashboard</a>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Plan ID</th>
                    <th>Feature Name</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($features as $feature): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($feature['id']); ?></td>
                        <td><?php echo htmlspecialchars($feature['plan_id']); ?></td>
                        <td><?php echo htmlspecialchars($feature['feature_name']); ?></td>
                        <td><?php echo htmlspecialchars($feature['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>