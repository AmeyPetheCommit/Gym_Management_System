<?php
session_start();
include 'config.php';
require 'vendor/autoload.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit;
}

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

$success = "";
$error = "";

if (!isset($_GET['session_id']) || !isset($_GET['plan_id'])) {
    $error = "Invalid request!";
} else {
    try {
        $session = \Stripe\Checkout\Session::retrieve($_GET['session_id']);
        $plan_id = intval($_GET['plan_id']);

        if ($session->payment_status === 'paid') {
            $user_email = $_SESSION['user'];

            $user_res = $conn->query("SELECT id, name FROM users WHERE email='$user_email'");
            $user = $user_res->fetch_assoc();
            $user_id = $user['id'];
            $user_name = $user['name'];

            $plan_res = $conn->query("SELECT * FROM membership_plans WHERE id=$plan_id");
            $plan = $plan_res->fetch_assoc();
            $duration = $plan['duration'];

            // calculate end date
            switch ($duration) {
                case '1month':   $end_date = date('Y-m-d', strtotime("+1 month")); break;
                case '3months':  $end_date = date('Y-m-d', strtotime("+3 months")); break;
                case '6months':  $end_date = date('Y-m-d', strtotime("+6 months")); break;
                case '9months':  $end_date = date('Y-m-d', strtotime("+9 months")); break;
                case '1year':    $end_date = date('Y-m-d', strtotime("+1 year")); break;
                default: $end_date = date('Y-m-d');
            }

            // save membership
            $sql = "INSERT INTO members (user_id, plan_id, end_date) VALUES ('$user_id', '$plan_id', '$end_date')";
            $conn->query($sql);

            $conn->query("UPDATE users SET role='member' WHERE id=$user_id");
            $_SESSION['role'] = 'member';

            $success = "🎉 Congratulations $user_name! You have successfully subscribed to the <b>"
                . $plan['plan_name'] . "</b> plan.";
        } else {
            $error = "❌ Payment failed or not verified!";
        }
    } catch (Exception $e) {
        $error = "⚠ Stripe Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Membership Confirmation - LiftKings</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body { font-family: 'Poppins', sans-serif; background:#fefdf8; margin:0; text-align:center; }
    header { background:#fff; border-bottom:3px solid #F7C600; padding:15px; }
    .logo { font-family:'Montserrat', sans-serif; font-size:2rem; }
    .logo .lift { color:#000; }
    .logo .kings { color:#F7C600; }
    .container { max-width:600px; margin:50px auto; background:#fff; padding:30px; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.1); }
    .success { color:green; font-size:1.2rem; }
    .error { color:red; font-size:1.2rem; }
    .plan-details { margin-top:20px; text-align:left; }
    .btn { display:inline-block; margin-top:20px; padding:12px 20px; background:#F7C600; color:#000; text-decoration:none; font-weight:bold; border-radius:8px; transition:.3s; }
    .btn:hover { background:#e0b400; }
  </style>
</head>
<body>
<header>
  <h1 class="logo"><span class="lift">Lift</span><span class="kings">Kings</span></h1>
</header>

<div class="container">
  <?php if ($success) { ?>
    <p class="success"><?php echo $success; ?></p>
    <div class="plan-details">
      <p><b>Plan Name:</b> <?php echo $plan['plan_name']; ?></p>
      <p><b>Price:</b> $<?php echo $plan['price']; ?></p>
      <p><b>Duration:</b> <?php echo $plan['duration']; ?></p>
      <p><b>Level:</b> <?php echo ucfirst($plan['level']); ?></p>
      <p><b>Valid Until:</b> <?php echo $end_date; ?></p>
    </div>
    <a class="btn" href="member_dashboard.php">Go to Member Dashboard</a>
  <?php } else { ?>
    <p class="error"><?php echo $error; ?></p>
    <a class="btn" href="membership.php">Back to Plans</a>
  <?php } ?>
</div>
</body>
</html>
