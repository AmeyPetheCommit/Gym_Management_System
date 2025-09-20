<?php
session_start();
include './config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Handle actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $plan_id = intval($_GET['id']);
    if ($_GET['action'] == 'delete') {
        $conn->query("DELETE FROM membership_plans WHERE id=$plan_id");
    }
    header("Location: admin_plans.php");
    exit;
}

// Fetch plans
$result = $conn->query("SELECT * FROM membership_plans ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Membership Plans - Admin Panel</title>
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
    .main-content { margin-left:240px; padding:20px 40px; width:calc(100% - 240px); }
    header h1 { font-size:22px; margin-bottom:20px; border-bottom:2px solid #F7C600; padding-bottom:6px; }
    
    table { width:100%; border-collapse:collapse; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0px 5px 15px rgba(0,0,0,0.1); }
    th,td { padding:12px; border-bottom:1px solid #eee; }
    th { background:#F7C600; color:#000; text-align:left; font-size:14px; text-transform:uppercase; }
    tr:hover { background:#fafafa; }

    .action-btn { padding:6px 10px; border-radius:5px; text-decoration:none; font-size:13px; margin-right:5px; font-weight:500; }
    .edit { background:#2196f3; color:#fff; }
    .delete { background:#e53935; color:#fff; }
    .action-btn:hover { opacity:.85; }
    .add-btn { display:inline-block; padding:10px 15px; background:#28a745; color:#fff; border-radius:6px; text-decoration:none; margin-bottom:15px; font-weight:bold; }
    .add-btn:hover { background:#218838; }
    .desc { max-width:250px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
  </style>
</head>
<body>
<body>
  <div class="sidebar">
    <h2>⚡ Admin Panel</h2>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="admin_products.php">Products</a>
    <a href="admin_plans.php" class="active">Membership Plans</a>
    <a href="admin_users.php">Users</a>
    <a href="admin_members.php" >Members 👑</a>
    <a href="admin_orders.php">Orders</a>
    <a href="admin_notices.php">Notices</a>
    <a href="logout.php">Logout</a>
  </div>

  <div class="main-content">
    <header>
      <h1>📋 Manage Membership Plans</h1>
    </header>

    <a href="admin_add_plan.php" class="add-btn">➕ Add New Plan</a>

    <table>
      <tr>
        <th>ID</th>
        <th>Plan Name</th>
        <th>Description</th>
        <th>Price</th>
        <th>Duration</th>
        <th>Level</th>
        <th>Actions</th>
      </tr>
      <?php while($row = $result->fetch_assoc()) { ?>
      <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo htmlspecialchars($row['plan_name']); ?></td>
        <td class="desc"><?php echo htmlspecialchars($row['description']); ?></td>
        <td>$<?php echo $row['price']; ?></td>
        <td><?php echo $row['duration']; ?></td>
        <td><?php echo ucfirst($row['level']); ?></td>
        <td>
          <a href="admin_edit_plan.php?id=<?php echo $row['id']; ?>" class="action-btn edit">Edit</a>
          <a href="admin_plans.php?action=delete&id=<?php echo $row['id']; ?>" class="action-btn delete" onclick="return confirm('Delete this plan?');">Delete</a>
        </td>
      </tr>
      <?php } ?>
    </table>
  </div>
</body>
</html>
