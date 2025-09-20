<?php
session_start();
include 'config.php';

// Only members can access
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'member') {
    header("Location: login.php");
    exit;
}

// Fetch products
$sql = "SELECT * FROM store_products WHERE stock > 0";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Nutrition Store - LiftKings</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body { font-family: 'Poppins', sans-serif; background: #1e1e2f; margin:0; color:#fefdf8; }
    .site-header { display:flex; justify-content:space-between; align-items:center; padding:15px 40px; background:#141424;;; border-bottom:3px solid #F7C600; }
    .logo { font-size:2rem; font-weight:bold; }
    .logo .lift { color:#fff; }
    .logo .kings { color:#F7C600; }
    nav a { margin:0 15px; text-decoration:none; color:#ddd; font-weight:500; transition:.3s; }
    nav a:hover { color:#F7C600; }

    .container { display:flex; gap:30px; max-width:1300px; margin:40px auto; padding:0 20px; }

    /* Sidebar Filters */
    .filters { flex:1; background:#2c2c3f; padding:20px; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.5); height: 480px; margin-top: 75px;}
    .filters h3 { margin-bottom:15px; color:#F7C600; border-bottom:2px solid #F7C600; padding-bottom:5px;  }
    .filter-group { margin-bottom:20px; padding-top: 40px;  }
    .filter-group strong { display:block; margin-bottom:8px; }
    .filter-group label { display:block; margin:6px 0; cursor:pointer; }
    .filter-group input { margin-right:8px; }
    .filter-group strong { color: #F7C600; }

    /* Product Grid */
    .products { flex:3; }
    h2 { margin-bottom:20px; text-align:center; color:#F7C600; }
    .grid { display:grid; grid-template-columns: repeat(auto-fit, minmax(250px,1fr)); gap:25px; }
    .card { background:#2c2c3f; border-radius:12px; padding:20px; box-shadow:0 5px 15px rgba(0,0,0,0.4); text-align:center; transition:.3s; }
    .card:hover { transform: translateY(-5px); }
    .card img { max-width:100%; height:200px; object-fit:contain; margin-bottom:15px; border-radius:8px; background:#000; }
    .card h3 { margin:10px 0; color:#fff; }
    .card p { color:#aaa; font-size:14px; }
    .price { font-weight:bold; font-size:16px; margin:10px 0; color:#F7C600; }
    .btn { display:inline-block; padding:10px 20px; background:#F7C600; color:#000; border-radius:8px; text-decoration:none; font-weight:bold; transition:.3s; }
    .btn:hover { background:#e0b400; }
    h1.section-title {
    color: #F7C600;
    font-size: 2rem;
    margin-bottom: 20px;
    border-bottom: 2px solid #F7C600;
    padding-bottom: 5px;
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
    </h1>  <nav>
    <a href="member_dashboard.php"><b>Home</b></a>
    <a href="nutrition_store.php"><b>Store</b></a>
    <a href="orders.php"><b>Orders</b></a>
    <a href="notices.php"><b>Notices</b></a>
    <a href="logout.php"><b>Logout</b></a>
  </nav>
</header>

<div class="container">
  <!-- Sidebar Filters -->
  <aside class="filters">
    <h3>Filter Products</h3>
    
    <div class="filter-group">
      <strong>Category</strong>
      <label><input type="checkbox" value="protein"> Protein</label>
      <label><input type="checkbox" value="vitamin"> Vitamins</label>
      <label><input type="checkbox" value="preworkout"> Pre-Workout</label>
      <label><input type="checkbox" value="snacks"> Snacks</label>
      <label><input type="checkbox" value="others"> Others</label>
    </div>

    <div class="filter-group">
      <strong>Price</strong>
      <label><input type="checkbox" value="low"> Under ₹1000</label>
      <label><input type="checkbox" value="mid"> ₹1000 - ₹3000</label>
      <label><input type="checkbox" value="high"> Above ₹3000</label>
    </div>
  </aside>

  <!-- Product Cards -->
  <section class="products">
    <h1 class="section-title"><i class="fa-solid fa-store"></i> LIFTKINGS STORE</h1>
    <div class="grid" id="productGrid">
      <?php while($row = $result->fetch_assoc()): ?>
        <div class="card" 
             data-category="<?php echo $row['category']; ?>" 
             data-price="<?php echo $row['price']; ?>">
          <?php if($row['image']): ?>
            <img src="uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
          <?php else: ?>
            <img src="uploads/default.png" alt="No Image">
          <?php endif; ?>
          <h3><?php echo $row['name']; ?></h3>
          <p><?php echo ucfirst($row['category']); ?></p>
          <div class="price">₹<?php echo $row['price']; ?></div>
          <a href="product_details.php?id=<?php echo $row['id']; ?>" class="btn">View Details</a>
        </div>
      <?php endwhile; ?>
    </div>
  </section>
</div>

<script>
  const checkboxes = document.querySelectorAll('.filters input[type="checkbox"]');
  const cards = document.querySelectorAll('.card');

  checkboxes.forEach(cb => {
    cb.addEventListener('change', () => {
      let activeFilters = { category: [], price: [] };

      checkboxes.forEach(c => {
        if (c.checked) {
          if (['protein','vitamin','preworkout','snacks','others'].includes(c.value)) {
            activeFilters.category.push(c.value);
          } else {
            activeFilters.price.push(c.value);
          }
        }
      });

      cards.forEach(card => {
        let show = true;
        let category = card.dataset.category;
        let price = parseFloat(card.dataset.price);

        // Category filter
        if (activeFilters.category.length && !activeFilters.category.includes(category)) {
          show = false;
        }

        // Price filter
        if (activeFilters.price.length) {
          let match = false;
          activeFilters.price.forEach(p => {
            if (p === 'low' && price < 1000) match = true;
            if (p === 'mid' && price >= 1000 && price <= 3000) match = true;
            if (p === 'high' && price > 3000) match = true;
          });
          if (!match) show = false;
        }

        card.style.display = show ? 'block' : 'none';
      });
    });
  });
</script>

</body>
</html>
