<?php
// filepath: c:\xampp\htdocs\bobatrade\includes\audit_log.php

function logAction($db, $user_id, $action, $details) {
    $stmt = $db->prepare("INSERT INTO audit_logs (user_id, action, details) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $action, $details]);
}
?>