<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\delete_user.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
require_once "../includes/audit_log.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

if (isset($_GET['id'])) {
    $user_id = (int)$_GET['id'];

    // Delete user
    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);

    // Log the action
    logAction($db, $_SESSION['user']['id'], 'Delete User', "Deleted user with ID $user_id");

    header("Location: manage_users.php?message=User+deleted+successfully");
    exit();
}
?>