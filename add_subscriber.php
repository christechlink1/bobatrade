<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\add_subscriber.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $db->prepare("INSERT INTO subscribers (email, subscribed_at) VALUES (?, datetime('now'))");
        $stmt->execute([$email]);

        header("Location: subscriber_manager.php?message=Subscriber+added+successfully");
        exit();
    } else {
        header("Location: subscriber_manager.php?error=Invalid+email+address");
        exit();
    }
}

$stmt = $db->query("SELECT email, subscribed_at FROM subscribers");
$subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>