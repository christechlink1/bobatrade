<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\reject_withdraw.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

if (isset($_GET['id'])) {
    $stmt = $db->prepare("UPDATE withdrawals SET status = 'rejected' WHERE id = ?");
    $stmt->execute([$_GET['id']]);

    header("Location: withdraw_management.php?message=Withdrawal+rejected+successfully");
    exit();
}
?>