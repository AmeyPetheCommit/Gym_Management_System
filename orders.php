    <?php
session_start();
include 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'member') {
    header("Location: login.php");
    exit;
}

$user_email = $_SESSION['user'];

// Get user_id
$sqlUser = "SELECT id FROM users WHERE email = ?";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param("s", $user_email);
$stmtUser->execute();
$resUser = $stmtUser->get_result();
$user = $resUser->fetch_assoc();

// Fetch orders
$sql = "SELECT o.id, p.name, o.quantity, o.total, o.payment_status, o.created_at 
        FROM orders o 
        JOIN store_products p ON o.product_id = p.id
        WHERE o.user_id = ?
        ORDER BY o.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$orders = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Orders - LiftKings</title>
  <style>
    body { font-family: Poppins, sans-serif; background:#1e1e2f; color:#fff; margin:0; }
        .site-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 40px;
    background-color: #141424;
    border-bottom: 3px solid #F7C600;
}
nav a { 
  margin: 0 15px; 
  text-decoration: none; 
  color: #f5f5f5; 
  font-weight: 500; 
}
nav a:hover { 
  color: #F7C600; 
}
nav a { 
  margin: 0 15px; 
  text-decoration: none; 
  color: #f5f5f5; 
  font-weight: 500; 
}
nav a:hover { 
  color: #F7C600; 
}
.logo { font-family: 'Montserrat', sans-serif; font-size: 2rem; }
.logo .lift { color: #fff; }
.logo .kings { color: #F7C600; }
    .container { max-width:1000px; margin:40px auto; padding:0 20px; }
    h2 { text-align:center; color:#F7C600; }
    table { width:100%; border-collapse:collapse; margin-top:20px; }
    th, td { padding:12px; border-bottom:1px solid #333; text-align:center; background-color: #14121bff;}
    th { background:#2c2c3f; color:#F7C600; }
  </style>
</head>
<body>
  <header class="site-header">
  <h1 class="logo">
      <span class="lift">
        LIFT
      </span>
      <span class="kings">
        K<i class="fa-solid fa-dumbbell fa-rotate-90 logo-dumbbell"></i>INGS
      </span>
    </h1>
  <nav>
    <a href="member_dashboard.php"><b>Home</b></a>
    <a href="nutrition_store.php"><b>Store</b></a>
    <a href="orders.php"><b>Orders</b></a>
    <a href="notices.php"><b>Notices</b></a>
    <a href="logout.php"><b>Logout</b></a>
  </nav>  
  </header>
  <div class="container">
    <h2>📦 My Orders</h2>
    <?php if ($orders->num_rows > 0): ?>
      <table>
        <tr>
          <th>Order ID</th>
          <th>Product</th>
          <th>Quantity</th>
          <th>Total (₹)</th>
          <th>Status</th>
          <th>Date</th>
        </tr>
        <?php while($row = $orders->fetch_assoc()): ?>
          <tr>
            <td>#<?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['quantity']; ?></td>
            <td><?php echo $row['total']; ?></td>
            <td><?php echo ucfirst($row['payment_status']); ?></td>
            <td><?php echo $row['created_at']; ?></td>
          </tr>
        <?php endwhile; ?>
      </table>
    <?php else: ?>
      <p style="text-align:center;">No orders found.</p>
    <?php endif; ?>
  </div>
</body>
</html>
