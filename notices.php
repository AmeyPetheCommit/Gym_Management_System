<?php
session_start();
include 'config.php';

// Only logged-in members
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'member') {
    header("Location: login.php");
    exit;
}

// Fetch all notices
$sql = "SELECT * FROM notices ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Gym Notices - LiftKings</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
/* New Theme: Dark & Modern */
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background-color: #1e1e2f;
    color: #f5f5f5;
}

 .site-header { 
    display:flex; 
    justify-content:space-between; 
    align-items:center; 
    padding:15px 40px; 
    background:#141424;;; 
    border-bottom:3px solid #F7C600; 
}
    .logo { 
        font-size:2rem; 
        font-weight:bold; 
    }
    .logo .lift { 
        color:#fff; 
    }
    .logo .kings { 
        color:#F7C600; 
    }
    nav a { 
        margin:0 15px; 
        text-decoration:none; 
        color:#ddd; 
        font-weight:500; 
        transition:.3s; }
    nav a:hover { 
        color:#F7C600; 
    }

.container { max-width: 900px; margin: 30px auto; padding: 0 20px; }

h1.section-title {
    color: #F7C600;
    font-size: 2rem;
    margin-bottom: 20px;
    border-bottom: 2px solid #F7C600;
    padding-bottom: 5px;
}

.notice-card {
    background-color: #2c2c3f;
    border-left: 5px solid #F7C600;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 12px rgba(0,0,0,0.5);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.notice-card:hover {
    transform: translateY(-5px);
    box-shadow: 0px 8px 20px rgba(0,0,0,0.7);
}

.notice-card h3 { margin-top: 0; color: #fff; }
.notice-card p { margin: 10px 0; color: #ddd; }
.notice-card .date { font-size: 0.85rem; color: #aaa; }

.btn-back {
    display: inline-block;
    margin-top: 15px;
    padding: 10px 15px;
    background: #F7C600;
    color: #000;
    border-radius: 6px;
    font-weight: bold;
    text-decoration: none;
    transition: 0.3s;
}
.btn-back:hover { background: #e0b400; }

@media(max-width:768px){
    .notice-card { padding: 15px; }
}
</style>
</head>
<body>

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
    <a href="notices.php"><b>Notices</b></a>
    <a href="logout.php"><b>Logout</b></a>
  </nav>
</header>

<div class="container">
    <h1 class="section-title"><i class="fa-solid fa-bullhorn"></i> Notices</h1>

    <?php
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            echo '<div class="notice-card">';
            echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
            echo '<p>' . nl2br(htmlspecialchars($row['message'])) . '</p>';
            echo '<div class="date">Posted on: ' . date('d M Y, H:i', strtotime($row['created_at'])) . '</div>';
            echo '</div>';
        }
    } else {
        echo '<p>No notices found at the moment.</p>';
    }
    ?>
    <a href="member_dashboard.php" class="btn-back">← Back to Dashboard</a>
</div>

</body>
</html>
