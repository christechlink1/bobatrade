<?php
require_once 'includes/auth.php';
requireLogin();

if (!isset($_SESSION['user'])) {
    echo "Error: User session data is missing.";
    exit();
}

$user = $_SESSION['user'];
$profileImagePath = "path/to/profile-image.jpg"; // Replace with actual path to profile image
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard - Bobatrade</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="https://code.iconify.design/3/3.0.0/iconify.min.js"></script>
    <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
    <style>
        /* General styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: rgb(26, 38, 66);
            color: #333;
        }

        h2 {
            text-align: center;
            margin-top: 80px;
            color: #fff;
        }

        /* Floating navigation bar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: rgb(255, 72, 16);
            color: #fff;
            padding: 10px 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: flex;
            align-items: center;
        }

        .menu-icon {
            font-size: 24px;
            cursor: pointer;
            margin-right: 10px;
        }

        .navbar h1 {
            margin: 0;
            font-size: 20px;
            color: #fff;
        }

        .navbar .nav-links {
            margin-left: auto;
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .navbar a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        /* Profile image or icon */
        .profile-container {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #fff; /* Background for fallback icon */
        }

        .profile-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-container .iconify {
            font-size: 24px;
            color: rgb(255, 72, 16); /* Match navbar color */
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 60px;
            left: 0;
            width: 250px;
            height: calc(100% - 60px);
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            z-index: 999;
            transition: transform 0.3s ease;
        }

        .sidebar.hidden {
            transform: translateX(-100%);
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            background: #f4f4f9;
            border-radius: 8px; /* Added border-radius for curved cards */
            transition: background 0.3s ease;
        }

        .sidebar ul li a:hover {
            background:rgba(219, 117, 0, 0.2); /* Slightly darker background on hover */
            color: #0056b3;
        }

        .sidebar .icon {
            font-size: 20px;
        }

        /* Dashboard container */
        .dashboard {
            margin-left: 270px;
            max-width: calc(100% - 270px);
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            transition: margin-left 0.3s ease;
        }

        .dashboard.sidebar-hidden {
            margin-left: 0;
        }

        /* Floating cards */
        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 250px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card .icon {
            font-size: 40px;
            color: #007bff;
            margin-bottom: 10px;
        }

        /* Stock Chart */
        .stock-chart {
            width: 100%;
            margin-top: 20px;
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .stock-chart select {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 16px;
        }

        /* Stock News */
        .stock-news {
            width: 100%;
            margin-top: 20px;
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .stock-news h3 {
            margin-bottom: 10px;
        }

        .stock-news ul {
            list-style: none;
            padding: 0;
        }

        .stock-news ul li {
            margin-bottom: 10px;
        }

        .stock-news ul li a {
            text-decoration: none;
            color: #007bff;
        }

        .stock-news ul li a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Floating navigation bar -->
    <div class="navbar">
        <span class="menu-icon iconify" data-icon="mdi:menu" onclick="toggleSidebar()"></span>
        <h1>Bobatrade Dashboard</h1>
        <div class="nav-links">
            <a href="profile.php">
                <div class="profile-container">
                    <?php if (file_exists($profileImagePath) && !empty($profileImagePath)): ?>
                        <img src="<?= htmlspecialchars($profileImagePath) ?>" alt="Profile Image">
                    <?php else: ?>
                        <span class="iconify" data-icon="mdi:account-circle-outline"></span>
                    <?php endif; ?>
                </div>
                Profile
            </a>
            <a href="logout.php"><span class="iconify" data-icon="mdi:logout"></span> Logout</a>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar hidden" id="sidebar">
        <ul>
            <li><a href="dashboard_user.php"><span class="icon iconify" data-icon="mdi:view-dashboard-outline"></span> Dashboard</a></li>
            <li><a href="deposit.php"><span class="icon iconify" data-icon="mdi:cash"></span> Deposit</a></li>
            <li><a href="withdraw.php"><span class="icon iconify" data-icon="mdi:bank-transfer-out"></span> Withdraw</a></li>
            <li><a href="investment.php"><span class="icon iconify" data-icon="mdi:cash"></span> Investment</a></li>
            <li><a href="plans.php"><span class="icon iconify" data-icon="mdi:package-variant"></span> Mining Plans</a></li>
            <li><a href="support.php"><span class="icon iconify" data-icon="mdi:ticket-outline"></span> Support Tickets</a></li>
            <li><a href="transactions.php"><span class="icon iconify" data-icon="mdi:history"></span> Transaction History</a></li>
            <li><a href="referrals.php"><span class="icon iconify" data-icon="mdi:account-multiple-outline"></span> Referrals</a></li>
            <li><a href="settings.php"><span class="icon iconify" data-icon="mdi:cog-outline"></span> Settings</a></li>
            <li><a href="faq.php"><span class="icon iconify" data-icon="mdi:help-circle-outline"></span> FAQ</a></li>
            <li><a href="blog.php"><span class="icon iconify" data-icon="mdi:blog"></span> Blog</a></li>
            <li><a href="news.php"><span class="icon iconify" data-icon="mdi:newspaper"></span> News</a></li>
            <li><a href="terms.php"><span class="icon iconify" data-icon="mdi:shield-check"></span> Terms of Service</a></li>
            <li><a href="privacy.php"><span class="icon iconify" data-icon="mdi:shield-lock"></span> Privacy Policy</a></li>
            <li><a href="contact.php"><span class="icon iconify" data-icon="mdi:email-outline"></span> Contact Us</a></li>
            <li><a href="about.php"><span class="icon iconify" data-icon="mdi:information-outline"></span> About Us</a></li>
            <li><a href="logout.php"><span class="icon iconify" data-icon="mdi:logout"></span> Logout</a></li>
        </ul>
    </div>

    <h2>Welcome, <?= htmlspecialchars($user['username']) ?> 👋</h2>

    <!-- User Coin Balances -->
    <div class="dashboard">
        <!-- Coin cards -->
        <?php
        $coins = [
            ['name' => 'Bitcoin', 'icon' => 'mdi:bitcoin', 'balance' => '0 BTC'],
            ['name' => 'Ethereum', 'icon' => 'mdi:ethereum', 'balance' => '0 ETH'],
            ['name' => 'Litecoin', 'icon' => 'mdi:litecoin', 'balance' => '0 LTC'],
            ['name' => 'Dogecoin', 'icon' => 'mdi:dogecoin', 'balance' => '0 DOGE'],
            ['name' => 'Ripple', 'icon' => 'mdi:ripple', 'balance' => '0 XRP'],
            ['name' => 'Cardano', 'icon' => 'mdi:cardano', 'balance' => '0 ADA'],
            ['name' => 'Polkadot', 'icon' => 'mdi:polkadot', 'balance' => '0 DOT'],
            ['name' => 'Binance Coin', 'icon' => 'mdi:binance', 'balance' => '0 BNB'],
            ['name' => 'Stellar', 'icon' => 'mdi:stellar', 'balance' => '0 XLM'],
            ['name' => 'Chainlink', 'icon' => 'mdi:chainlink', 'balance' => '0 LINK'],
        ];

        foreach ($coins as $coin) {
            echo "<div class='card'>
                    <span class='icon iconify' data-icon='{$coin['icon']}'></span>
                    <h3>{$coin['name']}</h3>
                    <p>Balance: {$coin['balance']}</p>
                  </div>";
        }
        ?>
    </div>

    <!-- Live Stock Chart -->
    <div class="stock-chart">
        <h3>Live Stock Chart</h3>
        <select id="stockSelector" onchange="updateStockChart()">
            <option value="NASDAQ:AAPL">Apple (AAPL)</option>
            <option value="NASDAQ:GOOGL">Google (GOOGL)</option>
            <option value="NASDAQ:AMZN">Amazon (AMZN)</option>
            <option value="NASDAQ:MSFT">Microsoft (MSFT)</option>
            <option value="NASDAQ:TSLA">Tesla (TSLA)</option>
        </select>
        <div class="tradingview-widget-container">
            <div id="tradingview_chart"></div>
        </div>
    </div>

    <!-- Stock News -->
    <div class="stock-news">
        <h3>Latest Stock News</h3>
        <ul>
            <li><a href="https://www.example.com/news1" target="_blank">Stock Market Update: Major Indices Rise</a></li>
            <li><a href="https://www.example.com/news2" target="_blank">Tech Stocks Lead Gains Amid Earnings Reports</a></li>
            <li><a href="https://www.example.com/news3" target="_blank">Energy Sector Sees Growth as Oil Prices Surge</a></li>
        </ul>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('hidden');
        }

        // Initialize TradingView widget
        function updateStockChart() {
            const selectedStock = document.getElementById('stockSelector').value;
            new TradingView.widget({
                "container_id": "tradingview_chart",
                "autosize": true,
                "symbol": selectedStock,
                "interval": "D",
                "timezone": "Etc/UTC",
                "theme": "light",
                "style": "1",
                "locale": "en",
                "toolbar_bg": "#f1f3f6",
                "enable_publishing": false,
                "hide_legend": false,
                "save_image": false,
                "studies": [],
                "show_popup_button": true,
                "popup_width": "900",
                "popup_height": "900"
            });
        }

        // Load default stock chart on page load
        document.addEventListener("DOMContentLoaded", function () {
            updateStockChart();
        });
    </script>
</body>
</html>
