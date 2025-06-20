<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\subscriber_manager.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

// Fetch all subscribers from the database
$stmt = $db->prepare("SELECT id, email, subscribed_at FROM subscribers ORDER BY subscribed_at DESC");
$stmt->execute();
$subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscriber Manager</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Subscriber Manager</h2>
        <a href="dashboard_admin.php" class="btn btn-primary mb-3">Back to Dashboard</a>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addSubscriberModal">Add Subscriber</button>
        <a href="export_subscribers.php" class="btn btn-secondary mb-3">Export Subscribers</a>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Subscribed At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subscribers as $subscriber): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($subscriber['id']); ?></td>
                        <td><?php echo htmlspecialchars($subscriber['email']); ?></td>
                        <td><?php echo htmlspecialchars($subscriber['subscribed_at']); ?></td>
                        <td>
                            <a href="delete_subscriber.php?id=<?php echo $subscriber['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this subscriber?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Add Subscriber Modal -->
        <div class="modal fade" id="addSubscriberModal" tabindex="-1" aria-labelledby="addSubscriberModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSubscriberModalLabel">Add Subscriber</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="add_subscriber.php">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" id="email" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Add Subscriber</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>