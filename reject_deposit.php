<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\reject_deposit.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

if (isset($_GET['id'])) {
    $stmt = $db->prepare("UPDATE deposits SET status = 'rejected' WHERE id = ?");
    $stmt->execute([$_GET['id']]);

    header("Location: deposit_management.php?message=Deposit+rejected+successfully");
    exit();
}
?>