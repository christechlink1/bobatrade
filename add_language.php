<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\add_language.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $code = trim($_POST['code']);
    $status = trim($_POST['status']);

    if (!empty($name) && !empty($code) && in_array($status, ['active', 'inactive'])) {
        $stmt = $db->prepare("INSERT INTO languages (name, code, status) VALUES (?, ?, ?)");
        $stmt->execute([$name, $code, $status]);

        header("Location: language_manager.php?message=Language+added+successfully");
        exit();
    } else {
        header("Location: language_manager.php?error=Invalid+input");
        exit();
    }
}
?>