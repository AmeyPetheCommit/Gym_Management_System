<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - LiftKings</title>
  <link rel="stylesheet" href="../css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
  margin-left: 250px;
  padding: 30px;
  flex: 1;
}

header h1 {
  margin: 0 0 20px 0;
}

.stats {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
}

.card {
  background: #fff;
  padding: 25px;
  border-radius: 10px;
  text-align: center;
  box-shadow: 0px 5px 15px rgba(0,0,0,0.1);
  transition: 0.3s;
}

.card:hover {
  transform: translateY(-5px);
}

.card h3 {
  margin: 0 0 10px;
  font-size: 18px;
  color: #555;
}

.card p {
  font-size: 22px;
  font-weight: bold;
  color: #F7C600;
}

  </style>
</head>
<body>
<div class="sidebar">
    <h2>⚡ Admin Panel</h2>
    <a href="admin_dashboard.php" class="active">Dashboard</a>
    <a href="admin_products.php">Products</a>
    <a href="admin_plans.php">Membership Plans</a>
    <a href="admin_users.php">Users</a>
    <a href="admin_members.php">Members 👑</a>
    <a href="admin_orders.php">Orders</a>
    <a href="admin_notices.php">Notices</a>
    <a href="logout.php">Logout</a>
  </div>

  <div class="main-content">
    <header>
      <h1>Welcome, Admin 👑</h1>
    </header>

    <section class="stats">
      <div class="card">
        <h3>👥 Users</h3>
        <p>120</p>
      </div>
      <div class="card">
        <h3>🏋️ Active Members</h3>
        <p>45</p>
      </div>
      <div class="card">
        <h3>🛒 Orders</h3>
        <p>32</p>
      </div>
      <div class="card">
        <h3>📢 Notices</h3>
        <p>5</p>
      </div>
    </section>
  </div>
</body>
</html>
