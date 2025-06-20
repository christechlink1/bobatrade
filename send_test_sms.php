<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\send_test_sms.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $test_phone = trim($_POST['test_phone']);
    $test_message = trim($_POST['test_message']);

    // Fetch SMS settings
    $stmt = $db->prepare("SELECT * FROM sms_settings");
    $stmt->execute();
    $smsSettings = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($smsSettings) {
        $api_url = $smsSettings['sms_api_url'];
        $api_key = $smsSettings['sms_api_key'];
        $sender_id = $smsSettings['sender_id'];

        // Send SMS via API
        $data = [
            'api_key' => $api_key,
            'sender_id' => $sender_id,
            'phone' => $test_phone,
            'message' => $test_message
        ];

        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code === 200) {
            header("Location: sms_manager.php?message=Test+SMS+sent+successfully");
        } else {
            header("Location: sms_manager.php?error=Failed+to+send+test+SMS");
        }
        exit();
    } else {
        header("Location: sms_manager.php?error=SMS+settings+not+configured");
        exit();
    }
}
?>