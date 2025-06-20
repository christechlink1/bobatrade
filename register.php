<?php
require_once "includes/db.php";

$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role'];

    // Check if email already exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        $msg = "Email already registered!";
    } else {
        // If registering as admin, check secret code
        if ($role === 'admin') {
            $code = $_POST['secret_code'] ?? '';
            if ($code !== '869811') {
                $msg = "Invalid admin secret code.";
            } else {
                $stmt = $db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
                $stmt->execute([$username, $email, $password, $role]);
                $msg = "Admin registration successful. <a href='login.php'>Login now</a>";
            }
        } else {
            // Register as a regular user
            $stmt = $db->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $password, $role]);
            $msg = "Registration successful. <a href='login.php'>Login now</a>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Bobatrade</title>
    <style>
        body {
            background-color: #0b0e11;
            color: white;
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #1e2329;
            padding: 2em;
            border-radius: 8px;
            width: 500px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
        }

        .container h2 {
            margin-bottom: 0.5em;
            font-size: 1.5em;
            color: #fcd535;
            text-align: left;
        }

        .container h3 {
            margin-bottom: 1em;
            font-size: 1.2em;
            color: white;
            text-align: left;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 12px;
            margin: 1em 0;
            border-radius: 5px;
            border: none;
            background: #2b3139;
            color: white;
            font-size: 1em;
        }

        input[type="email"]::placeholder {
            color: #aaa;
        }

        select {
            appearance: none;
            background: #2b3139;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
        }

        #secret-box {
            display: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #fcd535;
            color: black;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            margin: 1em 0;
            cursor: pointer;
            font-size: 1em;
        }

        button:hover {
            background-color: #e5c02a;
        }

        .social-button {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #2b3139;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 12px;
            margin: 0.5em 0;
            cursor: pointer;
            font-size: 1em;
        }

        .social-button img {
            width: 20px;
            height: 20px;
            margin-right: 10px;
        }

        a {
            color: #fcd535;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .message {
            margin-top: 1em;
            font-size: 0.9em;
        }

        .message.success {
            color: #4caf50;
        }

        .message.error {
            color: #f44336;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1em 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #444;
        }

        .divider:not(:empty)::before {
            margin-right: 0.5em;
        }

        .divider:not(:empty)::after {
            margin-left: 0.5em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Bobatrade</h2>
        <form method="POST">
            <h2>Create Account</h2>
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            
            <select name="role" id="role" required>
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>

            <div id="secret-box">
                <input type="password" name="secret_code" placeholder="Enter Admin Secret Code">
            </div>

            <button type="submit">Register</button>

            <div class="divider">or</div>

            <button class="social-button">
                <img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" alt="Google Logo"> Continue with Google
            </button>
            <button class="social-button">
                <img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg" alt="Apple Logo"> Continue with Apple
            </button>
            <button class="social-button">
                <img src="https://upload.wikimedia.org/wikipedia/commons/8/82/Telegram_logo.svg" alt="Telegram Logo"> Continue with Telegram
            </button>
            <p>By signing up, you agree to our <a href="terms.php">Terms of Service</a> and <a href="privacy.php">Privacy Policy</a>.</p>
            <p style="text-align:center;">Already have an account? <a href="login.php">Login</a></p>
            <p>Forgot your password? <a href="forgot_password.php">Reset it here</a></p>
            <p class="message <?php echo !empty($msg) && strpos($msg, 'successful') !== false ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($msg); ?>
            </p>
        </form>
    </div>

    <script>
        document.getElementById('role').addEventListener('change', function () {
            const isAdmin = this.value === 'admin';
            document.getElementById('secret-box').style.display = isAdmin ? 'block' : 'none';
        });
    </script>
</body>
</html>