<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\export_subscribers.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

// Fetch all subscribers
$stmt = $db->prepare("SELECT email, subscribed_at FROM subscribers");
$stmt->execute();
$subscribers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="subscribers.csv"');

// Output CSV
$output = fopen('php://output', 'w');
fputcsv($output, ['Email', 'Subscribed At']);
foreach ($subscribers as $subscriber) {
    fputcsv($output, $subscriber);
}
fclose($output);
exit();
?>