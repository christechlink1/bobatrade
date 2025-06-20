<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\advanced_analytics.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

// Fetch analytics data
// Total users
$totalUsersStmt = $db->prepare("SELECT COUNT(*) AS total_users FROM users");
$totalUsersStmt->execute();
$totalUsers = $totalUsersStmt->fetch(PDO::FETCH_ASSOC)['total_users'];

// Total transactions
$totalTransactionsStmt = $db->prepare("SELECT COUNT(*) AS total_transactions FROM transactions");
$totalTransactionsStmt->execute();
$totalTransactions = $totalTransactionsStmt->fetch(PDO::FETCH_ASSOC)['total_transactions'];

// Total logins
$totalLoginsStmt = $db->prepare("SELECT COUNT(*) AS total_logins FROM user_login_history");
$totalLoginsStmt->execute();
$totalLogins = $totalLoginsStmt->fetch(PDO::FETCH_ASSOC)['total_logins'];

// Recent user registrations
$recentUsersStmt = $db->prepare("SELECT id, username, created_at FROM users ORDER BY created_at DESC LIMIT 5");
$recentUsersStmt->execute();
$recentUsers = $recentUsersStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Analytics</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Advanced Analytics</h2>
        <a href="dashboard_admin.php" class="btn btn-primary mb-3">Back to Dashboard</a>

        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text"><?php echo htmlspecialchars($totalUsers); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Transactions</h5>
                        <p class="card-text"><?php echo htmlspecialchars($totalTransactions); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Logins</h5>
                        <p class="card-text"><?php echo htmlspecialchars($totalLogins); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <h3>Recent User Registrations</h3>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Registered At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentUsers as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>