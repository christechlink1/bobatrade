<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\deposit_management.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

// Fetch all deposit transactions from the database
$stmt = $db->prepare("SELECT id, user_id, method, coin, amount, details, status, created_at FROM deposits ORDER BY created_at DESC");
$stmt->execute();
$deposits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deposit Manager</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Deposit Manager</h2>
        <a href="dashboard_admin.php" class="btn">Back to Dashboard</a>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Method</th>
                    <th>Coin</th>
                    <th>Amount</th>
                    <th>Details</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($deposits as $deposit): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($deposit['id']); ?></td>
                        <td><?php echo htmlspecialchars($deposit['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($deposit['method']); ?></td>
                        <td><?php echo htmlspecialchars($deposit['coin']); ?></td>
                        <td><?php echo htmlspecialchars($deposit['amount']); ?></td>
                        <td><?php echo htmlspecialchars($deposit['details']); ?></td>
                        <td><?php echo htmlspecialchars($deposit['status']); ?></td>
                        <td><?php echo htmlspecialchars($deposit['created_at']); ?></td>
                        <td>
                            <?php if ($deposit['status'] === 'pending'): ?>
                                <a href="approve_deposit.php?id=<?php echo $deposit['id']; ?>" class="btn btn-primary">Approve</a>
                                <a href="reject_deposit.php?id=<?php echo $deposit['id']; ?>" class="btn btn-danger">Reject</a>
                            <?php else: ?>
                                <span><?php echo ucfirst($deposit['status']); ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>