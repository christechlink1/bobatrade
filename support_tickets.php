<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\support_tickets.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

// Fetch all support tickets from the database
$stmt = $db->prepare("SELECT id, user_id, subject, message, status, created_at FROM support_tickets ORDER BY created_at DESC");
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Tickets</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Support Tickets</h2>
        <a href="dashboard_admin.php" class="btn">Back to Dashboard</a>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ticket['id']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['subject']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['message']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['status']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['created_at']); ?></td>
                        <td>
                            <?php if ($ticket['status'] === 'open'): ?>
                                <a href="respond_ticket.php?id=<?php echo $ticket['id']; ?>" class="btn btn-primary">Respond</a>
                                <a href="close_ticket.php?id=<?php echo $ticket['id']; ?>" class="btn btn-danger">Close</a>
                            <?php else: ?>
                                <span><?php echo ucfirst($ticket['status']); ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>