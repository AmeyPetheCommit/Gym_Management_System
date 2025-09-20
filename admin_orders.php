<?php
session_start();
include './config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Handle actions (delete)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $order_id = intval($_GET['id']);
    if ($_GET['action'] == 'delete') {
        $conn->query("DELETE FROM orders WHERE id=$order_id");
    }
    header("Location: admin_orders.php");
    exit;
}

// Fetch orders with product and user info
$sql = "SELECT 
            o.id, 
            o.quantity, 
            o.total, 
            o.payment_status, 
            o.created_at, 
            p.name as product_name, 
            p.image as product_image,
            u.email as user_email
        FROM orders o
        JOIN store_products p ON o.product_id = p.id
        JOIN users u ON o.user_id = u.id
        ORDER BY o.created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders - Admin Panel</title>
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

/* ===== Orders Table ===== */
.orders-table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0px 5px 15px rgba(0,0,0,0.1);
}
.orders-table th, .orders-table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #eee;
}
.orders-table td{
      vertical-align: middle; 
}
.orders-table th {
  background: #F7C600;
  color: #000;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 14px;
}
.orders-table tr:hover {
  background: #f9f9f9;
}
.orders-table img {
  display: block;
  margin: auto;
  border: 1px solid #eee;
  background: #fafafa;
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
.img-preview {
  position: relative;
  display: inline-block;
}
.thumb {
  width: 40px;
  height: 40px;
  object-fit: cover;
  border-radius: 6px;
  cursor: pointer;
  border: 1px solid #ddd;
}
.preview-box {
  display: none;
  position: absolute;
  top: -10px;
  left: 50px;
  z-index: 100;
  border: 1px solid #ddd;
  background: #fff;
  padding: 5px;
  box-shadow: 0px 4px 10px rgba(0,0,0,0.15);
}
.preview-box img {
  width: 180px;
  height: auto;
  border-radius: 6px;
}
.img-preview:hover .preview-box {
  display: block;
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
    <a href="admin_orders.php" class="active">Orders</a>
    <a href="admin_notices.php">Notices</a>
    <a href="logout.php">Logout</a>
  </div>

  <div class="main-content">
    <header>
      <h1>📦 Manage Orders</h1>
    </header>

    <table class="orders-table">
      <tr>
        <th>Order ID</th>
        <th>Product</th>
        <th>Image</th>
        <th>Member</th>
        <th>Quantity</th>
        <th>Total (₹)</th>
        <th>Status</th>
        <th>Date</th>
        <th>Actions</th>
      </tr>
      <?php if ($result->num_rows > 0) { while($row = $result->fetch_assoc()) { ?>
      <tr>
        <td>#<?php echo $row['id']; ?></td>
        <td>
            <span title="<?php echo htmlspecialchars($row['product_name']); ?>">
              <?php 
                $name = htmlspecialchars($row['product_name']);
                echo (strlen($name) > 20) ? substr($name, 0, 20) . "..." : $name;
              ?>
            </span>
        </td>
        <td>
          <div class="img-preview">
            <?php if($row['product_image']): ?>
              <img src="uploads/<?php echo $row['product_image']; ?>" alt="<?php echo $row['product_name']; ?>" class="thumb">
            <?php else: ?>
              <img src="uploads/default.png" alt="No Image" class="thumb">
            <?php endif; ?>
          </div>
        </td>
        <td><?php echo $row['user_email']; ?></td>
        <td><?php echo $row['quantity']; ?></td>
        <td><?php echo $row['total']; ?></td>
        <td><?php echo ucfirst($row['payment_status']); ?></td>
        <td><?php echo $row['created_at']; ?></td>
        <td>
          <a href="admin_orders.php?action=delete&id=<?php echo $row['id']; ?>" class="action-btn delete" onclick="return confirm('Are you sure you want to delete this order?');">Delete</a>
        </td>
      </tr>
      <?php } } else { ?>
      <tr>
        <td colspan="9" style="text-align:center;">No orders found.</td>
      </tr>
      <?php } ?>
    </table>
  </div>
</body>
</html>
