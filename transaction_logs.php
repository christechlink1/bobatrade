<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\transaction_logs.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

// Fetch all transactions from the database
$stmt = $db->prepare("
    SELECT id, user_id, type, amount, status, created_at 
    FROM transactions 
    ORDER BY created_at DESC
");
$stmt->execute();
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Logs</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Transaction Logs</h2>
        <a href="dashboard_admin.php" class="btn btn-primary mb-3">Back to Dashboard</a>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($transaction['id']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['type']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['amount']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['status']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['created_at']); ?></td>
                        <td>
                            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#transactionModal<?php echo $transaction['id']; ?>">View</button>
                        </td>
                    </tr>

                    <!-- Modal for Transaction Details -->
                    <div class="modal fade" id="transactionModal<?php echo $transaction['id']; ?>" tabindex="-1" aria-labelledby="transactionModalLabel<?php echo $transaction['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="transactionModalLabel<?php echo $transaction['id']; ?>">Transaction Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>ID:</strong> <?php echo htmlspecialchars($transaction['id']); ?></p>
                                    <p><strong>User ID:</strong> <?php echo htmlspecialchars($transaction['user_id']); ?></p>
                                    <p><strong>Type:</strong> <?php echo htmlspecialchars($transaction['type']); ?></p>
                                    <p><strong>Amount:</strong> <?php echo htmlspecialchars($transaction['amount']); ?></p>
                                    <p><strong>Status:</strong> <?php echo htmlspecialchars($transaction['status']); ?></p>
                                    <p><strong>Date:</strong> <?php echo htmlspecialchars($transaction['created_at']); ?></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>