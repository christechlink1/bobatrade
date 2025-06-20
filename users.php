$stmt = $db->query("SELECT * FROM users");
$users = $stmt->fetchAll();
?>

<table border="1">
  <tr><th>ID</th><th>Username</th><th>Email</th><th>Action</th></tr>
  <?php foreach ($users as $user): ?>
  <tr>
    <td><?= $user['id'] ?></td>
    <td><?= $user['username'] ?></td>
    <td><?= $user['email'] ?></td>
    <td><a href="delete_user.php?id=<?= $user['id'] ?>">Delete</a></td>
  </tr>
  <?php endforeach; ?>
</table>
<?php
// Include footer