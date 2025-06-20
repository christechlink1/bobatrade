<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\resend_withdraw.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

if (isset($_GET['id'])) {
    // Update the status of the withdrawal to 'pending' to retry processing
    $stmt = $db->prepare("UPDATE withdrawals SET status = 'pending' WHERE id = ?");
    $stmt->execute([$_GET['id']]);

    header("Location: withdraw_management.php?message=Withdrawal+resent+successfully");
    exit();
}
?>