<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\edit_language.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $name = trim($_POST['name']);
    $code = trim($_POST['code']);
    $status = trim($_POST['status']);

    if ($id > 0 && !empty($name) && !empty($code) && in_array($status, ['active', 'inactive'])) {
        $stmt = $db->prepare("UPDATE languages SET name = ?, code = ?, status = ? WHERE id = ?");
        $stmt->execute([$name, $code, $status, $id]);

        header("Location: language_manager.php?message=Language+updated+successfully");
        exit();
    } else {
        header("Location: language_manager.php?error=Invalid+input");
        exit();
    }
}
?>