<?php
session_start();
include './config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Handle actions (delete)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    if ($_GET['action'] == 'delete') {
        $conn->query("DELETE FROM store_products WHERE id=$product_id");
    }
    header("Location: admin_products.php");
    exit;
}

// Fetch products
$result = $conn->query("SELECT * FROM store_products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Products - Admin Panel</title>
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

/* ===== Products Table ===== */
.products-table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0px 5px 15px rgba(0,0,0,0.1);
}

.products-table th, .products-table td {
  padding: 12px;
  text-align: left;
  border-bottom: 1px solid #eee;
}
.products-table td{
      vertical-align: middle; 
}
.products-table th {
  background: #F7C600;
  color: #000;
  font-weight: 600;
  text-transform: uppercase;
  font-size: 14px;
}

.products-table tr:hover {
  background: #f9f9f9;
}

.products-table img {
  display: block;
  margin: auto;
  border: 1px solid #eee;
  background: #fafafa;
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

.edit {
  background: #2196f3;
  color: #fff;
}

.delete {
  background: #e53935;
  color: #fff;
}

.action-btn:hover {
  opacity: 0.85;
}

.add-btn {
  display: inline-block;
  margin-bottom: 15px;
  padding: 10px 15px;
  background: #28a745;
  color: #fff;
  text-decoration: none;
  font-weight: bold;
  border-radius: 6px;
  transition: 0.3s;
}

.add-btn:hover {
  background: #218838;
}
.img-preview {
  position: relative;
  display: inline-block;
}

.thumb {
  width: 60px;
  height: 60px;
  object-fit: cover;
  border-radius: 6px;
  cursor: pointer;
  border: 1px solid #ddd;
}

.preview-box {
  display: none;
  position: absolute;
  top: -10px;
  left: 70px;
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
    <a href="admin_products.php" class="active">Products</a>
    <a href="admin_plans.php">Membership Plans</a>
    <a href="admin_users.php">Users</a>
    <a href="admin_members.php">Members 👑</a>
    <a href="admin_orders.php">Orders</a>
    <a href="admin_notices.php">Notices</a>
    <a href="logout.php">Logout</a>
  </div>

  <div class="main-content">
    <header>
      <h1>📦 Manage Products</h1>
    </header>

    <a href="admin_add_product.php" class="add-btn">+ Add New Product</a>

    <table class="products-table">
      <tr>
        <th>ID</th>
        <th>Image</th>
        <th>Name</th>
        <th>Description</th>
        <th>Price (₹)</th>
        <th>Stock</th>
        <th>Category</th>
        <th>Actions</th>
      </tr>
      <?php while($row = $result->fetch_assoc()) { ?>
      <tr>
        <td><?php echo $row['id']; ?></td>
        <td>
            <div class="img-preview">
          <?php if($row['image']): ?>
        <img src="uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?> " class="thumb" style="width:60px; height:60px; object-fit:cover; border-radius:6px;">
        
      <?php else: ?>
        <img src="uploads/default.png" alt="No Image" class="thumb" style="width:60px; height:60px; object-fit:cover; border-radius:6px;">
      <?php endif; ?>
            </div>
        </td>
        <td>
            <span title="<?php echo htmlspecialchars($row['name']); ?>">
                <?php 
                $desc = htmlspecialchars($row['name']);
                echo (strlen($desc) > 20) ? substr($desc, 0, 20) . "..." : $desc;
                ?>
            </span>
        </td>
        <td>
            <span title="<?php echo htmlspecialchars($row['description']); ?>">
                <?php 
                $desc = htmlspecialchars($row['description']);
                echo (strlen($desc) > 50) ? substr($desc, 0, 50) . "..." : $desc;
                ?>
            </span>
        </td>

        <td><?php echo number_format($row['price'], 2); ?></td>
        <td><?php echo $row['stock']; ?></td>
        <td><?php echo ucfirst($row['category']); ?></td>
        <td>
          <a href="admin_edit_product.php?id=<?php echo $row['id']; ?>" class="action-btn edit">Edit</a>
          <a href="admin_products.php?action=delete&id=<?php echo $row['id']; ?>" class="action-btn delete" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
        </td>
      </tr>
      <?php } ?>
    </table>
  </div>
</body>
</html>
