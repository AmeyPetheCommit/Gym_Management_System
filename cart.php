<?php
session_start();
include 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'member') {
    header("Location: login.php");
    exit;
}

// initialize cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add product
if (isset($_GET['add'])) {
    $id = intval($_GET['add']);
    if (!isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] = 1; // qty = 1
    } else {
        $_SESSION['cart'][$id]++; // increase qty
    }
    header("Location: cart.php");
    exit;
}

// Remove product
if (isset($_GET['remove'])) {
    $id = intval($_GET['remove']);
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit;
}

// Fetch cart products
$products = [];
$total = 0;
if (!empty($_SESSION['cart'])) {
    $ids = implode(",", array_keys($_SESSION['cart']));
    $sql = "SELECT * FROM store_products WHERE id IN ($ids)";
    $products = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cart - LiftKings</title>
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
    th, td { padding:12px; text-align:center; border-bottom:1px solid #333; }
    th { background:#2c2c3f; }
    .btn { padding:8px 12px; background:#F7C600; color:#000; text-decoration:none; border-radius:5px; font-weight:bold; margin:5px; }
    .btn:hover { background:#e0b400; }
    .total { text-align:right; margin-top:20px; font-size:1.3rem; }
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
    <h2>🛒 Your Cart</h2>
    <?php if(!empty($products) && $products->num_rows > 0): ?>
      <table>
        <tr>
          <th>Product</th>
          <th>Price</th>
          <th>Qty</th>
          <th>Total</th>
          <th>Action</th>
        </tr>
        <?php while($row = $products->fetch_assoc()): 
          $qty = $_SESSION['cart'][$row['id']];
          $lineTotal = $qty * $row['price'];
          $total += $lineTotal;
        ?>
          <tr>
            <td><?php echo $row['name']; ?></td>
            <td>₹<?php echo $row['price']; ?></td>
            <td><?php echo $qty; ?></td>
            <td>₹<?php echo $lineTotal; ?></td>
            <td><a href="cart.php?remove=<?php echo $row['id']; ?>" class="btn">Remove</a></td>
          </tr>
        <?php endwhile; ?>
      </table>
      <div class="total"><b>Grand Total: ₹<?php echo $total; ?></b></div>
      <div style="text-align:right; margin-top:20px;">
        <a href="checkout.php" class="btn">Proceed to Checkout</a>
      </div>
    <?php else: ?>
      <p style="text-align:center;">Your cart is empty.</p>
    <?php endif; ?>
  </div>
</body>
</html>
