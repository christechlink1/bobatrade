<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\add_page.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $slug = trim($_POST['slug']);
    $content = trim($_POST['content']);
    $status = trim($_POST['status']);

    if (!empty($title) && !empty($slug) && !empty($content) && in_array($status, ['published', 'unpublished'])) {
        $stmt = $db->prepare("INSERT INTO pages (title, slug, content, status) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $slug, $content, $status]);

        header("Location: page_builder.php?message=Page+added+successfully");
        exit();
    } else {
        header("Location: page_builder.php?error=Invalid+input");
        exit();
    }
}
?>