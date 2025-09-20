<?php
session_start();
include 'config.php';

// Ensure only logged-in members can access
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'member') {
    header("Location: login.php");
    exit;
}

$user_email = $_SESSION['user'];

// Fetch latest membership of this member
$sql = "SELECT u.name, m.start_date, m.end_date, p.* 
        FROM members m
        JOIN users u ON m.user_id = u.id
        JOIN membership_plans p ON m.plan_id = p.id
        WHERE u.email = ?
        ORDER BY m.id DESC LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$membership = $result->fetch_assoc();

if (!$membership) {
    echo "<p style='color:red; text-align:center;'>⚠ No membership found. Please buy a plan first.</p>";
    exit;
}

// Calculate progress safely
$progress = 0;
if (!empty($membership['start_date']) && !empty($membership['end_date'])) {
    $start = strtotime($membership['start_date']);
    $end = strtotime($membership['end_date']);
    $today = time();

    $total_days = ($end - $start) / (60 * 60 * 24);
    $used_days = ($today - $start) / (60 * 60 * 24);

    if ($total_days > 0) {
        $progress = ($used_days / $total_days) * 100;
        $progress = ($progress > 100) ? 100 : round($progress, 1);
    }
}

$progressColor = ($progress < 30) ? "#ff9900ff" : (($progress < 70) ? "#ffcc00" : "#28a745");

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Member Dashboard - LiftKings</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="./css/mem_dash.css">
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

  <!-- Welcome -->
  <h2 class="section-title">Welcome</h2>
  <div class="panel">
    <p>👋 Hello <b><?php echo $membership['name']; ?></b>, welcome to your exclusive Member Dashboard.</p>
    <p>You have unlocked access to premium resources, diet plans, and workout programs.</p>
  </div>

  <!-- Membership Info -->
  <h2 class="section-title">Your Membership</h2>
  <div class="panel">
    <p><b>Plan:</b> <?php echo $membership['plan_name']; ?> (<?php echo ucfirst($membership['level']); ?>)</p>
    <p><b>Duration:</b> <?php echo $membership['duration']; ?></p>
    <p><b>Amount:</b> $<?php echo $membership['price']; ?></p>
    <p><b>Start Date:</b> <?php echo $membership['start_date']; ?></p>
    <p><b>Valid Until:</b> <?php echo $membership['end_date']; ?></p>

    <p><b>Membership Progress:</b> <b>  <?php echo $progress; ?>% </b> </p>
    <div class="progress-bar">
      <div class="progress" style="width: <?php echo $progress; ?>%; background: <?php echo $progressColor; ?>;"></div>
    </div>
  </div>

  <!-- Diet Plan -->
  <!-- Diet Plan -->
<h2 class="section-title"><i class="fa-solid fa-apple-whole"></i> Diet Plan</h2>
<div class="panel">
    <ul>
        <?php
        if(!empty($membership['diet_plan'])) {
            // Split by new lines to create list items
            $diet_items = explode("\n", $membership['diet_plan']);
            foreach($diet_items as $item) {
                echo '<li>' . htmlspecialchars(trim($item)) . '</li>';
            }
        } else {
            echo '<li>No diet plan available.</li>';
        }
        ?>
    </ul>
    <a href="download_diet.php" class="btn">Download Diet Guide (PDF)</a>
</div>

<!-- Workout Plan -->
<h2 class="section-title"><i class="fa-solid fa-dumbbell"></i> Workout Plan</h2>
<div class="panel">
    <ul>
        <?php
        if(!empty($membership['workout_plan'])) {
            // Split by new lines to create list items
            $workout_items = explode("\n", $membership['workout_plan']);
            foreach($workout_items as $item) {
                echo '<li>' . htmlspecialchars(trim($item)) . '</li>';
            }
        } else {
            echo '<li>No workout plan available.</li>';
        }
        ?>
    </ul>
    <a href="download_workout.php" class="btn">Download Workout Guide (PDF)</a>
</div>


  <!-- Classes -->
  <h2 class="section-title"><i class="fa-solid fa-calendar"></i> Upcoming Classes</h2>
  <div class="panel">
    <ul>
      <li>🔥 HIIT Bootcamp – Monday 7am</li>
      <li>🧘 Yoga Flow – Wednesday 6pm</li>
      <li>💪 Strength Training – Friday 5pm</li>
      <li>🥊 Boxing Basics – Saturday 10am</li>
    </ul>
  </div>

  <h2 class="section-title"><i class="fa-solid fa-receipt"></i> Receipt </h2>
<div class="panel">
    
    <a href="receipt.php" class="btn">Download Receipt (PDF)</a>
</div>

</div>
</body>
</html>
