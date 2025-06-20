<?php
include('includes/db.php');
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Bobatrade</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        .header {
            background-color: #1e2329;
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .header h1 {
            margin: 0;
            font-size: 2em;
        }

        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: #fcd535;
            color: black;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }

        .back-button:hover {
            background-color: #e5c02a;
        }

        .statistics-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }

        .card {
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            width: 200px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            margin: 0;
            font-size: 1.2em;
            color: #333;
        }

        .card p {
            margin: 10px 0 0;
            font-size: 1.5em;
            font-weight: bold;
            color: #1e2329;
        }

        .tabs {
            display: flex;
            justify-content: center;
            background-color: #1e2329;
            padding: 10px;
        }

        .tabs button {
            background-color: #2b3139;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 0 5px;
            cursor: pointer;
            border-radius: 5px;
        }

        .tabs button.active {
            background-color: #fcd535;
            color: black;
        }

        .tabs button:hover {
            background-color: #fcd535;
            color: black;
        }

        .tab-content {
            display: none;
            padding: 20px;
            background-color: white;
            border: 1px solid #ccc;
            margin: 20px;
            border-radius: 5px;
        }

        .tab-content.active {
            display: block;
        }

        form {
            margin: 20px 0;
        }

        form input, form select, form textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form button {
            background-color: #fcd535;
            color: black;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }

        form button:hover {
            background-color: #e5c02a;
        }
    </style>
</head>
<body>
    <div class="header">
        <button class="back-button" onclick="history.back()">← Back</button>
        <h1>My Profile</h1>
    </div>

    <div class="statistics-container">
        <div class="card">
            <h3>Investments</h3>
            <p>$10,000</p>
        </div>
        <div class="card">
            <h3>Withdrawals</h3>
            <p>$5,000</p>
        </div>
        <div class="card">
            <h3>Downlines</h3>
            <p>15</p>
        </div>
        <div class="card">
            <h3>Wallet</h3>
            <p>$2,000</p>
        </div>
        <div class="card">
            <h3>Earnings</h3>
            <p>$1,500</p>
        </div>
        <div class="card">
            <h3>Referral Bonus</h3>
            <p>$500</p>
        </div>
    </div>

    <div class="tabs">
        <button class="tab-button active" data-tab="profile-settings">Profile Settings</button>
        <button class="tab-button" data-tab="reset-password">Reset Password</button>
        <button class="tab-button" data-tab="upload-profile-image">Upload Profile Image</button>
        <button class="tab-button" data-tab="identity-verification">Identity Verification</button>
        <button class="tab-button" data-tab="2fa-security">2FA Security</button>
        <button class="tab-button" data-tab="kyc-upgrade">KYC Level Upgrade</button>
        <button class="tab-button" data-tab="add-crypto-wallet">Add Crypto Wallet</button>
        <button class="tab-button" data-tab="add-bank-details">Add Bank Details</button>
    </div>

    <div class="tab-content active" id="profile-settings">
        <h2>Profile Settings</h2>
        <form method="post">
            <input type="text" name="firstname" placeholder="First Name" value="<?= htmlspecialchars($user['firstname'] ?? '') ?>">
            <input type="text" name="lastname" placeholder="Last Name" value="<?= htmlspecialchars($user['lastname'] ?? '') ?>">
            <input type="email" name="email" placeholder="Email Address" value="<?= htmlspecialchars($user['email'] ?? '') ?>" readonly>
            <input type="text" name="username" placeholder="Username" value="<?= htmlspecialchars($user['username'] ?? '') ?>">
            <input type="text" name="phone" placeholder="Phone Number" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
            <button type="submit">Update Profile</button>
        </form>
    </div>

    <div class="tab-content" id="reset-password">
        <h2>Reset Password</h2>
        <form method="post">
            <input type="password" name="old_password" placeholder="Enter Old Password">
            <input type="password" name="new_password" placeholder="Enter New Password">
            <input type="password" name="confirm_password" placeholder="Confirm New Password">
            <button type="submit">Reset Password</button>
        </form>
    </div>

    <div class="tab-content" id="upload-profile-image">
        <h2>Upload Profile Image</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="profile_image">
            <button type="submit">Upload Image</button>
        </form>
    </div>

    <div class="tab-content" id="identity-verification">
        <h2>Identity Verification</h2>
        <form method="post" enctype="multipart/form-data">
            <select name="id_type">
                <option value="passport">Passport</option>
                <option value="driver_license">Driver's License</option>
                <option value="national_id">National ID</option>
            </select>
            <input type="file" name="id_document">
            <button type="submit">Upload ID</button>
        </form>
    </div>

    <div class="tab-content" id="2fa-security">
        <h2>2FA Security</h2>
        <form method="post">
            <button type="submit" name="enable_2fa">Enable 2FA</button>
            <button type="submit" name="disable_2fa">Disable 2FA</button>
        </form>
    </div>

    <div class="tab-content" id="kyc-upgrade">
        <h2>KYC Level Upgrade</h2>
        <form method="post" enctype="multipart/form-data">
            <textarea name="selfie_instructions" readonly>Take a selfie of yourself holding your ID with your full face clearly shown.</textarea>
            <input type="file" name="selfie">
            <textarea name="proof_of_address_instructions" readonly>Valid documents are utility bills and bank statements.</textarea>
            <input type="file" name="proof_of_address">
            <button type="submit">Submit KYC</button>
        </form>
    </div>

    <div class="tab-content" id="add-crypto-wallet">
        <h2>Add Crypto Wallet</h2>
        <form method="post">
            <input type="text" name="coin_name" placeholder="Coin Name">
            <input type="text" name="coin_host" placeholder="Coin Host">
            <input type="text" name="wallet_address" placeholder="Wallet Address">
            <button type="submit">Add Wallet</button>
        </form>
    </div>

    <div class="tab-content" id="add-bank-details">
        <h2>Add Bank Details</h2>
        <form method="post">
            <input type="text" name="bank_name" placeholder="Bank Name">
            <input type="text" name="account_name" placeholder="Account Name">
            <input type="text" name="account_number" placeholder="Account Number">
            <input type="text" name="routing_number" placeholder="Routing Number">
            <button type="submit">Add Bank Details</button>
        </form>
    </div>

    <script>
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));

                button.classList.add('active');
                document.getElementById(button.dataset.tab).classList.add('active');
            });
        });
    </script>
</body>
</html>