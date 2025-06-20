<?php
require_once "../includes/auth.php";
require_once "../includes/db.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Admin Dashboard</h2>
        <p>Welcome, Admin <?php echo htmlspecialchars($_SESSION['user']['username']); ?>!</p>
        <a href="logout.php" class="btn">Logout</a>

        <h3>Admin Features</h3>
        <ul class="feature-list">
            <li><a href="manage_users.php" class="btn">User Manager</a></li>
            <li><a href="miners_manager.php" class="btn">Miners Manager</a></li>
            <li><a href="plan_manager.php" class="btn">Plan Manager</a></li>
            <li><a href="plan_feature_manager.php" class="btn">Plan Feature Manager</a></li>
            <li><a href="sales_log.php" class="btn">All Sales Log</a></li>
            <li><a href="deposit_management.php" class="btn">Deposit Manager</a></li>
            <li><a href="withdraw_management.php" class="btn">Withdraw Manager</a></li>
            <li><a href="support_tickets.php" class="btn">Support Ticket</a></li>
            <li><a href="transaction_logs.php" class="btn">Transaction Logs</a></li>
            <li><a href="user_login_history.php" class="btn">User Login History</a></li>
            <li><a href="language_manager.php" class="btn">Language Manager</a></li>
            <li><a href="subscriber_manager.php" class="btn">Subscriber Manager</a></li>
            <li><a href="basic_settings.php" class="btn">Basic Settings</a></li>
            <li><a href="logo_favicon_settings.php" class="btn">Logo & Favicon Settings</a></li>
            <li><a href="extensions_settings.php" class="btn">Extensions Settings</a></li>
            <li><a href="seo_manager.php" class="btn">SEO Manager</a></li>
            <li><a href="email_settings.php" class="btn">Email Settings Tools</a></li>
            <li><a href="sms_manager.php" class="btn">SMS Manager</a></li>
            <li><a href="sms_api_settings.php" class="btn">SMS API Settings</a></li>
            <li><a href="page_builder.php" class="btn">Page Builder</a></li>
            <li><a href="frontend_settings.php" class="btn">Frontend Settings & Section Manager</a></li>
            <li><a href="advanced_analytics.php" class="btn">Advanced Analytics</a></li>
            <li><a href="notification_manager.php" class="btn">Notification Manager</a></li>
            <li><a href="audit_logs.php" class="btn">Audit Logs</a></li>
        </ul>
    </div>
</body>
</html>