<?php
session_start();
include './config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $notice_id = intval($_GET['id']);
    $conn->query("DELETE FROM notices WHERE id=$notice_id");
    header("Location: admin_notices.php");
    exit;
}

// Handle add notice (simple version)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_notice'])) {
    $title = $_POST['title'];
    $message = $_POST['message'];
    $type = $_POST['type'];
    $created_at = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("INSERT INTO notices (title, message, type, created_at) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $message, $type, $date);
    $stmt->execute();
    header("Location: admin_notices.php");
    exit;
}

// Fetch notices
$result = $conn->query("SELECT * FROM notices ORDER BY created_at DESC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Notices - Admin Panel</title>
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
.sidebar a.active{
  background: #F7C600;
  color: #000;
}
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
.notices-table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0px 5px 15px rgba(0,0,0,0.1);
}
.notices-table th, .notices-table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #eee;
}
.notices-table th {
  background: #F7C600;
  color: #000;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 14px;
}
.notices-table tr:hover {
  background: #f9f9f9;
}
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
.delete {
  background: #e53935;
  color: #fff;
}
.action-btn:hover {
  opacity: 0.85;
}
.add-notice-btn, .add-notice-form button {
  display: inline-block;
  margin-bottom: 15px;
  padding: 10px 15px;
  background: #2196f3;
  color: #fff;
  text-decoration: none;
  font-weight: bold;
  border-radius: 6px;
  transition: 0.3s;
  border: none;
  font-size: 14px;
}
.add-notice-btn:hover, .add-notice-form button:hover {
  background: #1768aa;
}
.add-notice-form {
  margin-bottom: 25px;
  background: #fff;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0px 5px 15px rgba(0,0,0,0.08);
}
.add-notice-form label {
  font-weight: 500;
  margin-right: 8px;
}
.add-notice-form input, .add-notice-form textarea, .add-notice-form select {
  width: 100%;
  padding: 8px;
  margin: 6px 0 14px 0;
  border-radius: 5px;
  border: 1px solid #ddd;
  font-family: inherit;
  font-size: 15px;
}
.notice-type-info {
  background: #5bc0de;
  color: #fff;
  padding: 2px 8px; 
  border-radius: 4px;
  font-size: 13px;
  display: inline-block;
  font-weight: 500;
}
.notice-type-success {
  background: #28a745;
  color: #fff;
  padding: 2px 8px;
  border-radius: 4px;
  font-size: 13px;
  display: inline-block;
  font-weight: 500;
}
.notice-type-warning {
  background: #ffc107;
  color: #000;
  padding: 2px 8px;
  border-radius: 4px;
  font-size: 13px;
  display: inline-block;
  font-weight: 500;
}
.notice-type-error {
  background: #e53935;
  color: #fff;
  padding: 2px 8px;
  border-radius: 4px;
  font-size: 13px;
  display: inline-block;
  font-weight: 500;
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
    <a href="admin_members.php">Members 👑</a>
    <a href="admin_orders.php">Orders</a>
    <a href="admin_notices.php" class="active">Notices</a>
    <a href="logout.php">Logout</a>
  </div>
  <div class="main-content">
    <header>
      <h1>📢 Manage Notices</h1>
    </header>

    <!-- Add Notice Form -->
    <button class="add-notice-btn" onclick="document.getElementById('addNoticeForm').style.display='block';this.style.display='none';">+ Add New Notice</button>
    <form method="post" class="add-notice-form" id="addNoticeForm" style="display:none;">
      <label>Title</label>
      <input type="text" name="title" required maxlength="100">
      <label>Message</label>
      <textarea name="message" required rows="3" maxlength="255"></textarea>
      <label>Type</label>
      <select name="type">
        <option value="info">Info</option>
        <option value="success">Urgent</option>
        <option value="warning">General</option>
        <option value="error">Warning</option>
      </select>
      <button type="submit" name="add_notice">Add Notice</button>
    </form>
    <!-- Notices Table -->
    <table class="notices-table">
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Message</th>
        <th>Type</th>
        <th>Date</th>
        <th>Actions</th>
      </tr>
      <?php if ($result->num_rows > 0) { while ($row = $result->fetch_assoc()) { ?>
      <tr>
        <td><?php echo $row['id']; ?></td>
        <td>
          <span title="<?php echo htmlspecialchars($row['title']); ?>">
            <?php 
              $title = htmlspecialchars($row['title']);
              echo (strlen($title) > 30) ? substr($title, 0, 30) . "..." : $title;
            ?>
          </span>
        </td>
        <td>
          <span title="<?php echo htmlspecialchars($row['message']); ?>">
            <?php 
                $msg = htmlspecialchars($row['message']);
                echo (strlen($msg) > 80) ? substr($msg, 0, 80) . "..." : $msg;
            ?>
            </span>

        </td>
        <td>
          <span>
            <?php echo htmlspecialchars($row['type']); ?>
        </span>
        <td><?php echo $row['created_at']; ?></td>
        <td>
          <a href="admin_notices.php?action=delete&id=<?php echo $row['id']; ?>" class="action-btn delete"
             onclick="return confirm('Are you sure you want to delete this notice?');">Delete</a>
        </td>
      </tr>
      <?php } } else { ?>
      <tr>
        <td colspan="6" style="text-align:center;">No notices found.</td>
      </tr>
      <?php } ?>
    </table>
  </div>
  <script>
    // Hide form after submission and allow toggling
    if(window.location.href.indexOf('admin_notices.php') > -1) {
      document.getElementById('addNoticeForm').onsubmit = function() {
        this.style.display = 'none';
        document.querySelector('.add-notice-btn').style.display = 'inline-block';
      };
    }
  </script>
</body>
</html>
