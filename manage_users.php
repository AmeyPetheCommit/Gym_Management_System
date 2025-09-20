<?php
include 'config.php';
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

// Delete user if requested
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM users WHERE id=$id");
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage Users</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>All Users</h2>
  <table border="1" cellpadding="10">
    <tr>
      <th>ID</th><th>Username</th><th>Role</th><th>Action</th>
    </tr>
    <?php
    $result = $conn->query("SELECT * FROM users where role='user'");
    while ($row = $result->fetch_assoc()) {
      echo "<tr>
              <td>{$row['id']}</td>
              <td>{$row['username']}</td>
              <td>{$row['role']}</td>
              <td><a href='manage_users.php?delete={$row['id']}'>Delete</a></td>
            </tr>";
    }
    ?>
  </table>
  <a href="admin_dashboard.php">Back</a>
</body>
</html>
