<?php
session_start();
include 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
  header("Location: login.php");
  exit;
}


$sql = "SELECT * FROM membership_plans ORDER BY price ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Membership Plans - LiftKings</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="./css/membership.css">
</head>
<body>

  <header class="site-header">
    <h1 class="logo"><span class="lift">Lift</span><span class="kings">Kings</span></h1>
    <nav>
      <a href="index.php">Dashboard</a>
      <a href="index.php#contact">Contact</a>
      <a href="logout.php">Logout</a>
    </nav>
  </header>

  <div class="container">
    <!-- Filters Sidebar -->
    <aside class="filters">
      <h3>Filter Plans</h3>

      <div class="filter-group">
        <strong>Duration</strong>
        <label><input type="checkbox" value="1month"> 1 Month</label>
        <label><input type="checkbox" value="3months"> 3 Months</label>
        <label><input type="checkbox" value="6months"> 6 Months</label>
        <label><input type="checkbox" value="9months"> 9 Months</label>
        <label><input type="checkbox" value="1year"> 1 Year</label>
      </div>

      <div class="filter-group">
        <strong>Level</strong>
        <label><input type="checkbox" value="basic"> Basic</label>
        <label><input type="checkbox" value="gold"> Gold</label>
        <label><input type="checkbox" value="premium"> Premium</label>
        <label><input type="checkbox" value="vip"> VIP</label>
      </div>

      <div class="filter-group">
        <strong>Price</strong>
        <label><input type="checkbox" value="low"> Under $30</label>
        <label><input type="checkbox" value="mid"> $30 - $70</label>
        <label><input type="checkbox" value="mid2"> $70 - $100</label>
        <label><input type="checkbox" value="high"> Above $100</label>
      </div>

      <button class="clear-btn" id="clearFilters">Clear Filters</button>
    </aside>

    <!-- Membership Plans -->
    <section class="plans">
      <?php while ($row = $result->fetch_assoc()) { 
        // assign price range category
        $priceCat = ($row['price'] < 30) ? "low" : (($row['price'] <= 70) ? "mid" : (($row['price'] <= 100) ? "mid2" : "high"));
      ?>
        <div class="card" 
             data-duration="<?php echo $row['duration']; ?>" 
             data-level="<?php echo strtolower($row['level']); ?>" 
             data-price="<?php echo $priceCat; ?>">
          <h3><?php echo htmlspecialchars($row['plan_name']); ?></h3>
          <p><?php echo htmlspecialchars($row['description']); ?></p>
          <p>Duration: <?php echo $row['duration']; ?></p>
          <p>Level: <?php echo ucfirst($row['level']); ?></p>
          <div class="price">$<?php echo $row['price']; ?></div>
          <form method="post" action="buy_membership.php">
            <input type="hidden" name="plan_id" value="<?php echo $row['id']; ?>">
            <button type="submit">Choose Plan</button>
          </form>
        </div>
      <?php } ?>
    </section>
  </div>

  <script src="./scripts/membership.js"></script>
</body>
</html>
