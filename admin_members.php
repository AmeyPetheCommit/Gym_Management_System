<?php
session_start();
include './config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Handle actions (downgrade / delete member)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    if ($_GET['action'] == 'delete') {
        // delete user
        $conn->query("DELETE FROM users WHERE id=$user_id");
        $conn->query("DELETE FROM members WHERE user_id=$user_id");
    } elseif ($_GET['action'] == 'downgrade') {
        // downgrade role
        $conn->query("UPDATE users SET role='user' WHERE id=$user_id");
        $conn->query("DELETE FROM members WHERE user_id=$user_id");
    }
    header("Location: admin_members.php");
    exit;
}

// Fetch only members
$result = $conn->query("
  SELECT u.id, u.name, u.email, u.role, m.start_date, m.end_date, p.plan_name
  FROM users u
  JOIN members m ON u.id = m.user_id
  JOIN membership_plans p ON m.plan_id = p.id
  WHERE u.role='member'
  ORDER BY u.id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Members - Admin Panel</title>
  <link rel="stylesheet" href="../css/admin.css">
  <style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Poppins', sans-serif;
  background: #fefdf8;
  display: flex;
  min-height: 100vh;
  color: #333;
}

/* ===== Sidebar ===== */
.sidebar {
  width: 240px;
  background: #111;
  color: #fff;
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 15px;
  min-height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
}

.sidebar h2 {
  text-align: center;
  margin-bottom: 25px;
  font-size: 20px;
  color: #F7C600;
}

.sidebar a {
  display: block;
  padding: 10px 15px;
  text-decoration: none;
  color: #fff;
  border-radius: 6px;
  transition: 0.3s;
  font-weight: 500;
}

.sidebar a:hover,
.sidebar a.active {
  background: #F7C600;
  color: #000;
}

/* ===== Main Content ===== */
.main-content {
  margin-left: 240px;
  padding: 20px 40px;
  width: calc(100% - 240px);
}

header h1 {
  margin-bottom: 20px;
  font-size: 24px;
  border-bottom: 2px solid #F7C600;
  padding-bottom: 8px;
}

/* ===== Members Table ===== */
.users-table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0px 5px 15px rgba(0,0,0,0.1);
}

.users-table th, .users-table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #eee;
}

.users-table th {
  background: #F7C600;
  color: #000;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 14px;
}

.users-table tr:hover {
  background: #f9f9f9;
}

/* ===== Buttons ===== */
.action-btn {
  padding: 6px 12px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 13px;
  margin-right: 5px;
  text-decoration: none;
  font-weight: bold;
}

.downgrade {
  background: #ff9800;
  color: #fff;
}

.delete {
  background: #e53935;
  color: #fff;
}

.action-btn:hover {
  opacity: 0.85;
}
  </style>
</head>
<body>
  <div class="sidebar">
    <h2>⚡ Admin Panel</h2>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="admin_products.php">Products</a>

    <a href="admin_plans.php">Membership Plans</a>
    <a href="admin_users.php">Users</a>
    <a href="admin_members.php" class="active">Members 👑</a>
    <a href="admin_orders.php">Orders</a>
    <a href="admin_notices.php">Notices</a>
    <a href="logout.php">Logout</a>
  </div>

  <div class="main-content">
    <header>
      <h1>👑 Manage Members</h1>
    </header>

    <table class="users-table">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Plan</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Role</th>
        <th>Actions</th>
      </tr>
      <?php while($row = $result->fetch_assoc()) { ?>
      <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo htmlspecialchars($row['name']); ?></td>
        <td><?php echo htmlspecialchars($row['email']); ?></td>
        <td><?php echo htmlspecialchars($row['plan_name']); ?></td>
        <td><?php echo $row['start_date']; ?></td>
        <td><?php echo $row['end_date']; ?></td>
        <td><?php echo ucfirst($row['role']); ?></td>
        <td>
          <a href="admin_members.php?action=downgrade&id=<?php echo $row['id']; ?>" class="action-btn downgrade">Downgrade</a>
          <a href="admin_members.php?action=delete&id=<?php echo $row['id']; ?>" class="action-btn delete" onclick="return confirm('Are you sure you want to delete this member?');">Delete</a>
        </td>
      </tr>
      <?php } ?>
    </table>
  </div>
</body>
</html>