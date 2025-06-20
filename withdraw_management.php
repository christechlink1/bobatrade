<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\withdraw_management.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

// Fetch all withdrawal requests from the database
$stmt = $db->prepare("SELECT id, user_id, method, amount, status, created_at FROM withdrawals ORDER BY created_at DESC");
$stmt->execute();
$withdrawals = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdraw Manager</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Withdraw Manager</h2>
        <a href="dashboard_admin.php" class="btn">Back to Dashboard</a>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Method</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($withdrawals as $withdrawal): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($withdrawal['id']); ?></td>
                        <td><?php echo htmlspecialchars($withdrawal['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($withdrawal['method']); ?></td>
                        <td><?php echo htmlspecialchars($withdrawal['amount']); ?></td>
                        <td><?php echo htmlspecialchars($withdrawal['status']); ?></td>
                        <td><?php echo htmlspecialchars($withdrawal['created_at']); ?></td>
                        <td>
                            <?php if ($withdrawal['status'] === 'pending'): ?>
                                <a href="approve_withdraw.php?id=<?php echo $withdrawal['id']; ?>" class="btn btn-primary">Approve</a>
                                <a href="reject_withdraw.php?id=<?php echo $withdrawal['id']; ?>" class="btn btn-danger">Reject</a>
                            <?php elseif ($withdrawal['status'] === 'rejected'): ?>
                                <a href="resend_withdraw.php?id=<?php echo $withdrawal['id']; ?>" class="btn btn-warning">Resend</a>
                            <?php else: ?>
                                <span><?php echo ucfirst($withdrawal['status']); ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>