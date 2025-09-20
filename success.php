<?php
session_start();
include 'config.php';
require 'vendor/autoload.php';

if (!isset($_GET['session_id'])) {
    die("No session found");
}

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

$session = \Stripe\Checkout\Session::retrieve($_GET['session_id']);

$success = false;

if ($session->payment_status === 'paid') {
    $user_email = $_SESSION['user'];

    // Get user_id
    $sqlUser = "SELECT id FROM users WHERE email = ?";
    $stmtUser = $conn->prepare($sqlUser);
    $stmtUser->bind_param("s", $user_email);
    $stmtUser->execute();
    $resUser = $stmtUser->get_result();
    $user = $resUser->fetch_assoc();

    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product_id => $qty) {
            // Get product price
            $sqlProd = "SELECT price FROM store_products WHERE id = ?";
            $stmtProd = $conn->prepare($sqlProd);
            $stmtProd->bind_param("i", $product_id);
            $stmtProd->execute();
            $resProd = $stmtProd->get_result();
            $prod = $resProd->fetch_assoc();

            $total = $prod['price'] * $qty;

            // Insert into orders
            $sqlOrder = "INSERT INTO orders (user_id, product_id, quantity, total, payment_status, stripe_session_id) 
                         VALUES (?, ?, ?, ?, ?, ?)";
            $stmtOrder = $conn->prepare($sqlOrder);
            $status = "paid";
            $stmtOrder->bind_param("iiidss", $user['id'], $product_id, $qty, $total, $status, $_GET['session_id']);
            $stmtOrder->execute();
        }
        // Clear cart
        $_SESSION['cart'] = [];
    }

    $success = true;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Confirmation - LiftKings</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body { font-family: 'Poppins', sans-serif; background:#1e1e2f; margin:0; }
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
    .container { max-width:600px; margin:80px auto; background:#2c2c3f; color:#fff; padding:40px; border-radius:12px; box-shadow:0 5px 15px rgba(255, 255, 255, 0.1); text-align:center; }
    h2 { margin-bottom:20px; }
    .success { color:#e0b400; }
    .error { color:red; }
    .btn { display:inline-block; margin-top:20px; padding:12px 25px; background:#F7C600; color:#000; border-radius:8px; text-decoration:none; font-weight:bold; transition:.3s; }
    .btn:hover { background:#e0b400; }
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
  <?php if ($success) { ?>
    <h2 class="success"><i class="fa-solid fa-circle-check"></i> Payment Successful!</h2>
    <p> Thank you for your purchase. Your order has been recorded successfully.</p>
    <a href="orders.php" class="btn"><i class="fa-solid fa-box"></i> View My Orders</a>
    <a href="nutrition_store.php" class="btn"><i class="fa-solid fa-store"></i> Back to Store</a>
  <?php } else { ?>
    <h2 class="error"><i class="fa-solid fa-triangle-exclamation"></i> Payment Failed!</h2>
    <p>⚠ Something went wrong. Please try again.</p>
    <a href="nutrition_store.php" class="btn">Back to Store</a>
  <?php } ?>
</div>

</body>
</html>
