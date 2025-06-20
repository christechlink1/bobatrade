<?php
require_once 'includes/auth.php';
requireLogin();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Support Tickets - Bobatrade</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Support Tickets</h1>
        <p>Submit or view your support tickets.</p>
        <form method="post" class="mt-4">
            <div class="mb-3">
                <input type="text" name="subject" class="form-control" placeholder="Subject">
            </div>
            <div class="mb-3">
                <textarea name="message" class="form-control" placeholder="Message"></textarea>
            </div>
            <button name="submit" class="btn btn-primary">Send Ticket</button>
        </form>
        <?php
        if (isset($_POST['submit'])) {
            $stmt = $db->prepare("INSERT INTO tickets (user_id, subject, message) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $_POST['subject'], $_POST['message']]);
            echo "<div class='alert alert-success mt-3'>Ticket submitted!</div>";
        }
        ?>
        <div class="mt-4">
            <p>Support tickets will be displayed here.</p>
            <!-- Add live support ticket data here -->
        </div>
    </div>
</body>
</html>