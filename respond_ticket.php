<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\respond_ticket.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

if (isset($_GET['id'])) {
    $stmt = $db->prepare("SELECT id, user_id, subject, message FROM support_tickets WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ticket) {
        echo "Ticket not found.";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = trim($_POST['response']);
    $stmt = $db->prepare("UPDATE support_tickets SET admin_response = ?, status = 'responded' WHERE id = ?");
    $stmt->execute([$response, $_GET['id']]);

    header("Location: support_tickets.php?message=Ticket+responded+successfully");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respond to Ticket</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Respond to Ticket</h2>
        <a href="support_tickets.php" class="btn">Back to Support Tickets</a>
        <form method="POST">
            <p><strong>Subject:</strong> <?php echo htmlspecialchars($ticket['subject']); ?></p>
            <p><strong>Message:</strong> <?php echo htmlspecialchars($ticket['message']); ?></p>
            <label for="response">Your Response:</label>
            <textarea name="response" id="response" rows="5" required></textarea>
            <button type="submit" class="btn btn-primary">Send Response</button>
        </form>
    </div>
</body>
</html>