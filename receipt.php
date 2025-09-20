<?php
session_start();
include 'config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'member') {
    header("Location: login.php");
    exit;
}

$user_email = $_SESSION['user'];

// Get user & membership details
$sql = "SELECT u.name, m.id as membership_id, m.end_date, p.plan_name, p.duration, p.price, p.level 
        FROM members m
        JOIN users u ON m.user_id = u.id
        JOIN membership_plans p ON m.plan_id = p.id
        WHERE u.email = ?
        ORDER BY m.id DESC LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows == 0) {
    die("<h2 style='text-align:center;color:red;'>❌ No active membership found.</h2>");
}

$membership = $res->fetch_assoc();

// Generate receipt data
$receipt_id = "LK-" . strtoupper(uniqid($membership['membership_id']."-"));
$issue_date = date("Y-m-d H:i:s");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Membership Receipt - LiftKings</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #141424;
      margin: 0;
      padding: 0;
    }
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
    .container {
      max-width: 750px;
      margin: 40px auto;
      padding: 20px;
    }
    .receipt-card {
      background: #fff;
      padding: 25px 40px;
      border-radius: 12px;
      box-shadow: 0px 5px 15px rgba(0,0,0,0.1);
      position: relative;
      overflow: hidden;
    }
    .watermark {
      position: absolute;
      top: 30%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 4rem;
      font-weight: 900;
      color: rgba(247, 198, 0, 0.1);
      white-space: nowrap;
      pointer-events: none;
    }
    .receipt-card h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }
    .receipt-meta {
      margin-bottom: 20px;
      font-size: 14px;
      color: #555;
    }
    .receipt-meta p { margin: 5px 0; }
    .receipt-details p {
      font-size: 15px;
      margin: 8px 0;
      color: #444;
    }
    hr {
      margin: 20px 0;
      border: none;
      border-top: 1px solid #eee;
    }
    .btn-download {
      display: block;
      margin: 20px auto;
      padding: 12px 20px;
      background: #F7C600;
      color: #000;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      transition: .3s;
    }
    .btn-download:hover {
      background: #e0b400;
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
  <div id="receipt" class="receipt-card">
    <div class="watermark">LiftKings</div>
    <h2>Membership Receipt</h2>
    <div class="receipt-meta">
      <p><b>Receipt ID:</b> <?php echo $receipt_id; ?></p>
      <p><b>Date of Issue:</b> <?php echo $issue_date; ?></p>
    </div>
    <hr>
    <div class="receipt-details">
      <p><b>Member:</b> <?php echo $membership['name']; ?></p>
      <p><b>Plan:</b> <?php echo $membership['plan_name']; ?> (<?php echo ucfirst($membership['level']); ?>)</p>
      <p><b>Duration:</b> <?php echo $membership['duration']; ?></p>
      <p><b>Price:</b> $<?php echo $membership['price']; ?></p>
      <p><b>Valid Until:</b> <?php echo $membership['end_date']; ?></p>
    </div>
    <hr>
    <p style="font-size:14px;color:#777;text-align:center;">
      This is a computer-generated receipt from <b>LiftKings</b>.
    </p>
  </div>

  <button onclick="downloadReceipt()" class="btn-download">
    ⬇ Download Receipt (PDF)
  </button>
</div>

<script>
function downloadReceipt() {
  const element = document.getElementById("receipt");
  const opt = {
    margin: 0.5,
    filename: 'membership_receipt.pdf',
    image: { type: 'jpeg', quality: 0.98 },
    html2canvas: { scale: 2 },
    jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
  };
  html2pdf().set(opt).from(element).save();
}
</script>

</body>
</html>
