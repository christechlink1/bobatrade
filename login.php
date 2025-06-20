<?php
require_once "includes/db.php";
require_once "includes/auth.php";

$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'] ?? null;

    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($password) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['user'] = $user;

                // After successful login
                session_start();
                $user_id = $_SESSION['user']['id'];
                $ip_address = $_SERVER['REMOTE_ADDR'];
                $device_info = $_SERVER['HTTP_USER_AGENT'];

                $stmt = $db->prepare("INSERT INTO user_login_history (user_id, ip_address, device_info) VALUES (?, ?, ?)");
                $stmt->execute([$user_id, $ip_address, $device_info]);

                if ($user['role'] === 'admin') {
                    header("Location: http://localhost/bobatrade/admin/dashboard_admin.php");
                } else {
                    header("Location: dashboard_user.php");
                }
                exit();
            } else {
                $msg = "Invalid password.";
            }
        }
    } else {
        header("Location: register.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bobatrade</title>
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <style>
        body {
            background-color:rgba(11, 14, 17, 0.3);
            color: white;
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
        }

        .container {
            background-color: #1e2329;
            padding: 2em;
            border-radius: 8px;
            width: 400px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
            position: relative;
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

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 1em 0;
            border-radius: 5px;
            border: none;
            background: #2b3139;
            color: white;
            font-size: 1em;
        }

        input[type="email"]::placeholder,
        input[type="password"]::placeholder {
            color: #aaa;
        }

        .password-container {
            position: relative;
        }

        .password-container input {
            width: calc(100% - 40px);
            display: inline-block;
        }

        .password-container .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #aaa;
            font-size: 1.2em;
        }

        button {
            width: 100%;
            padding: 12px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            margin: 1em 0;
            cursor: pointer;
            font-size: 1em;
        }

        button.yellow {
            background-color: #fcd535;
            color: black;
        }

        button.yellow:hover {
            background-color: #e5c02a;
        }

        button.white {
            background-color: white;
            color: black;
            border: 1px solid #ccc;
        }

        button.white:hover {
            background-color: #f5f5f5;
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

        .social-button {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: white;
            color: black;
            border: 1px solid #ccc;
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

        .social-button:hover {
            background-color: #f5f5f5;
        }

        .back-icon {
            position: absolute;
            top: 1em;
            left: 1em;
            cursor: pointer;
            color: #fcd535;
            font-size: 1.2em;
        }

        /* Add styles for the footer text */
        p {
            color: black; /* Set the font color to black */
            font-size: 0.9em; /* Adjust the font size if needed */
            text-align: center; /* Center-align the text */
        }

        p a {
            color: #007bff; /* Set the link color to blue */
            text-decoration: none; /* Remove underline from links */
        }

        p a:hover {
            text-decoration: underline; /* Add underline on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Bobatrade</h2>
        <h3>Login</h3>
        <form method="POST" id="loginForm">
            <div id="emailStep">
                <input type="email" name="email" id="email" placeholder="Email" required>
                <button type="button" id="nextButton" class="yellow">Next</button>
            </div>

            <div id="passwordStep" style="display: none;">
                <span class="back-icon" id="backButton">← Back</span>
                <div class="password-container">
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <span class="toggle-password" id="togglePassword">
                        <span class="iconify" data-icon="mdi:eye-outline"></span>
                    </span>
                </div>
                <button type="submit" class="yellow">Login</button>
                <p><a href="forgot_password.php">Forgot password?</a></p>
            </div>

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

            <?php if (!empty($msg)): ?>
                <p class="message error"><?php echo htmlspecialchars($msg); ?></p>
            <?php endif; ?>
        </form>
    </div>

    <p>By signing up, you agree to our <a href="terms.php">Terms of Service</a> and <a href="privacy.php">Privacy Policy</a>.</p>
    <p>Create a Bobatrade account? <a href="register.php">Register</a></p>

    <script>
        const emailStep = document.getElementById('emailStep');
        const passwordStep = document.getElementById('passwordStep');
        const nextButton = document.getElementById('nextButton');
        const backButton = document.getElementById('backButton');
        const emailInput = document.getElementById('email');
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        nextButton.addEventListener('click', () => {
            const email = emailInput.value.trim();
            if (email) {
                emailStep.style.display = 'none';
                passwordStep.style.display = 'block';
            } else {
                alert('Please enter a valid email address.');
            }
        });

        backButton.addEventListener('click', () => {
            passwordStep.style.display = 'none';
            emailStep.style.display = 'block';
        });

        togglePassword.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            const icon = togglePassword.querySelector('.iconify');
            icon.setAttribute('data-icon', type === 'password' ? 'mdi:eye-outline' : 'mdi:eye-off-outline');
        });
    </script>
</body>
</html>
