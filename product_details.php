<?php
session_start();
include 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'member') {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: nutrition_store.php");
    exit;
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM store_products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "<p style='color:red;text-align:center;'>❌ Product not found.</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo $product['name']; ?> - LiftKings</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body { 
      font-family: 'Poppins', sans-serif;
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

    .container { 
      max-width:1000px;
      margin:80px auto; 
      display:flex; 
      gap:40px; 
      padding:0 20px; 
    }
    .image { 
      flex:1; 
      background:#2c2c3f; 
      padding:20px; 
      border-radius:12px; 
      text-align:center; 
    }
    .image img { 
      max-width:100%; 
      height:350px; 
      object-fit:contain;
     }
    .details { 
      flex:2; 
      background:#2c2c3f; 
      padding:30px; 
      border-radius:12px; 
    }
    h2 { 
      color:#F7C600;
      margin-bottom:15px; 
    }
    .price { 
      font-size:1.5rem; 
      font-weight:bold; 
      color:#F7C600; 
      margin:15px 0; 
    }
    .btn { 
      display:inline-block; 
      padding:12px 20px; 
      background:#F7C600; 
      color:#000; 
      border-radius:8px; 
      text-decoration:none; 
      font-weight:bold; 
      margin:10px 5px 0 0; 
      transition:.3s; 
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
        K<i class="fa-solid fa-dumbbell fa-rotate-90 logo-dumbbell"></i>NGS
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
    <div class="image">
      <?php if($product['image']): ?>
        <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
      <?php else: ?>
        <img src="uploads/default.png" alt="No Image">
      <?php endif; ?>
    </div>
    <div class="details">
      <h2><?php echo $product['name']; ?></h2>
      <p><b>Category:</b> <?php echo ucfirst($product['category']); ?></p>
      <p><b>Description:</b> <?php echo $product['description']; ?></p>
      <p class="price">₹<?php echo $product['price']; ?></p>
      <p><b>Stock:</b> <?php echo $product['stock']; ?></p>

      <!-- Buttons -->
      <a href="wishlist.php?add=<?php echo $product['id']; ?>" class="btn"><i class="fa fa-heart"></i> Add to Wishlist</a>
      <a href="cart.php?add=<?php echo $product['id']; ?>" class="btn"><i class="fa fa-shopping-cart"></i> Add to Cart</a>
      <a href="checkout.php?buy=<?php echo $product['id']; ?>" class="btn"><i class="fa fa-bolt"></i> Buy Now</a>
    </div>
  </div>
</body>
</html>
