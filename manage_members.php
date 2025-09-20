<?php
include 'config.php';
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Manage Members</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h2>All Members</h2>
  <table border="1" cellpadding="10">
    <tr>
      <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Package</th>
    </tr>
    <?php
    $result = $conn->query("SELECT * FROM users where role = 'member'");
    while ($row = $result->fetch_assoc()) {
      echo "<tr>
              <td>{$row['id']}</td>
              <td>{$row['name']}</td>
              <td>{$row['email']}</td>
              <td>{$row['phone']}</td>
              <td>{$row['package']}</td>
            </tr>";
    }
    ?>
  </table>
  <a href="admin_dashboard.php">Back</a>
</body>
</html>