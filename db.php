<?php
// db.php - SQLite connection
try {
    $db = new PDO("sqlite:" . __DIR__ . "/../bobatrade.sqlite");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
