<?php
// filepath: c:\xampp\htdocs\bobatrade\admin\edit_user.php
require_once "../includes/auth.php";
require_once "../includes/db.php";
require_once "../includes/audit_log.php";
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied";
    exit();
}

if (isset($_GET['id'])) {
    $stmt = $db->prepare("SELECT id, username, email, role FROM users WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "User not found.";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $stmt = $db->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
    $stmt->execute([$username, $email, $role, $_GET['id']]);

    // Log the action
    logAction($db, $_SESSION['user']['id'], 'Edit User', "Edited user with ID {$_GET['id']}");

    header("Location: manage_users.php?message=User+updated+successfully");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Edit User</h2>
        <a href="manage_users.php" class="btn">Back to User Manager</a>
        <form method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            <br>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            <br>
            <label for="role">Role:</label>
            <select name="role" id="role" required>
                <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
            </select>
            <br>
            <button type="submit" class="btn">Update User</button>
        </form>
    </div>
</body>
</html>