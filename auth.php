<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirectIfNotLoggedIn() {
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit();
    }
}

function isAdmin() {
    return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

function requireAdmin() {
    if (!isLoggedIn() || !isAdmin()) {
        header("Location: login.php");
        exit();
    }
}
?>
