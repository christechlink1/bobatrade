<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\delete_subscriber.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

if (isset($_GET['id'])) {
    $stmt = $db->prepare("DELETE FROM subscribers WHERE id = ?");
    $stmt->execute([$_GET['id']]);

    header("Location: subscriber_manager.php?message=Subscriber+deleted+successfully");
    exit();
}
?>