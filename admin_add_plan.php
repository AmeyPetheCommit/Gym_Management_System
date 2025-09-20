<?php
session_start();
include './config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['plan_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $level = $_POST['level'];

    $sql = "INSERT INTO membership_plans (plan_name, description, price, duration, level) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdss", $name, $description, $price, $duration, $level);

    if ($stmt->execute()) {
        $message = "<p style='color:green;'>✅ Plan added successfully!</p>";
    } else {
        $message = "<p style='color:red;'>❌ Error: " . $stmt->error . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Membership Plan</title>
  <style>
    body { font-family: 'Poppins', sans-serif; background:#fefdf8; margin:0; }
    .container { max-width:600px; margin:50px auto; background:#fff; padding:30px; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.1); }
    h2 { text-align:center; margin-bottom:20px; }
    label { display:block; margin:10px 0 5px; font-weight:500; }
    input, textarea, select { width:100%; padding:10px; margin-bottom:15px; border:1px solid #ddd; border-radius:8px; }
    button { width:100%; padding:12px; background:#F7C600; border:none; border-radius:8px; font-weight:bold; cursor:pointer; transition:.3s; }
    button:hover { background:#e0b400; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Add New Membership Plan</h2>
    <?php echo $message; ?>
    <form method="post">
      <label>Plan Name</label>
      <input type="text" name="plan_name" required>

      <label>Description</label>
      <textarea name="description" rows="4" required></textarea>

      <label>Price ($)</label>
      <input type="number" step="0.01" name="price" required>

      <label>Duration</label>
      <select name="duration">
        <option value="1month">1 Month</option>
        <option value="3months">3 Months</option>
        <option value="6months">6 Months</option>
        <option value="9months">9 Months</option>
        <option value="1year">1 Year</option>
      </select>

      <label>Level</label>
      <select name="level">
        <option value="Beginner">Beginner</option>
        <option value="Intermediate">Intermediate</option>
        <option value="Advance">Advance</option>
        <option value="Professional">Professional</option>
      </select>

      <button type="submit">Add Plan</button>

      <a href="admin_plans.php" class="back">⬅ Back to Plans</a>
    </form>
  </div>
</body>
</html>
