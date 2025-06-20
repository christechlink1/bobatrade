<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\close_ticket.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

if (isset($_GET['id'])) {
    $stmt = $db->prepare("UPDATE support_tickets SET status = 'closed' WHERE id = ?");
    $stmt->execute([$_GET['id']]);

    header("Location: support_tickets.php?message=Ticket+closed+successfully");
    exit();
}
?>