<?php
session_start();
include 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'member') {
    header("Location: login.php");
    exit;
}

// initialize wishlist
if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

// Add product to wishlist
if (isset($_GET['add'])) {
    $id = intval($_GET['add']);
    if (!in_array($id, $_SESSION['wishlist'])) {
        $_SESSION['wishlist'][] = $id;
    }
    header("Location: wishlist.php");
    exit;
}

// Remove product
if (isset($_GET['remove'])) {
    $id = intval($_GET['remove']);
    $_SESSION['wishlist'] = array_diff($_SESSION['wishlist'], [$id]);
    header("Location: wishlist.php");
    exit;
}

// Fetch wishlist products
$products = [];
if (!empty($_SESSION['wishlist'])) {
    $ids = implode(",", $_SESSION['wishlist']);
    $sql = "SELECT * FROM store_products WHERE id IN ($ids)";
    $products = $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Wishlist - LiftKings</title>
  <style>
    body { 
      font-family: Poppins, sans-serif; 
      background:#1e1e2f; 
      color:#fff; 
      margin:0; 
    }
.site-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 40px;
    background-color: #141424;
    border-bottom: 3px solid #F7C600;
}
.logo { 
  font-family: 'Montserrat', sans-serif; 
  font-size: 2rem; }
.logo .lift { 
  color: #fff; 
}
.logo .kings { 
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
nav a { 
  margin: 0 15px; 
  text-decoration: none; 
  color: #f5f5f5; 
  font-weight: 500; 
}
nav a:hover { 
  color: #F7C600; 
}
    .container { 
      max-width:1000px; 
      margin:40px auto; 
      padding:0 20px; 
    }
    h2 { 
      text-align:center; 
      color:#F7C600; 
    }
    .card { 
      background:#2c2c3f; 
      padding:20px; 
      margin:15px 0; 
      border-radius:10px; 
      display:flex; 
      justify-content:space-between; 
      align-items:center; 
    }
    .card img { 
      width:80px; 
      height:80px; 
      object-fit:contain; 
      border-radius:8px; 
      background:#000; 
    }
    .btn { 
      padding:8px 12px; 
      background:#F7C600; 
      color:#000; 
      text-decoration:none; 
      border-radius:5px; 
      font-weight:bold; 
      margin-left:10px; 
    }
    .btn:hover { 
      background:#e0b400;
    }
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
    <h2>❤️ Your Wishlist</h2>
    <?php if(!empty($products) && $products->num_rows > 0): ?>
      <?php while($row = $products->fetch_assoc()): ?>
        <div class="card">
          <div>
            <img src="uploads/<?php echo $row['image']; ?>" alt="">
            <b><?php echo $row['name']; ?></b> - ₹<?php echo $row['price']; ?>
          </div>
          <div>
            <a href="cart.php?add=<?php echo $row['id']; ?>" class="btn">Add to Cart</a>
            <a href="wishlist.php?remove=<?php echo $row['id']; ?>" class="btn">Remove</a>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p style="text-align:center;">Your wishlist is empty.</p>
    <?php endif; ?>
  </div>
</body>
</html>
