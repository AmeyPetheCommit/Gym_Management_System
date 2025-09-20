<?php
session_start();
include './config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: admin_plans.php");
    exit;
}

$plan_id = intval($_GET['id']);
$message = "";

// Fetch existing plan
$sql = "SELECT * FROM membership_plans WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $plan_id);
$stmt->execute();
$result = $stmt->get_result();
$plan = $result->fetch_assoc();

if (!$plan) {
    die("❌ Plan not found.");
}

// Update plan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['plan_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $level = $_POST['level'];

    $sqlUpdate = "UPDATE membership_plans 
                  SET plan_name=?, description=?, price=?, duration=?, level=? 
                  WHERE id=?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("ssdssi", $name, $description, $price, $duration, $level, $plan_id);

    if ($stmtUpdate->execute()) {
        $message = "<p style='color:green;'>✅ Plan updated successfully!</p>";
        // Refresh plan data
        $plan['plan_name'] = $name;
        $plan['description'] = $description;
        $plan['price'] = $price;
        $plan['duration'] = $duration;
        $plan['level'] = $level;
    } else {
        $message = "<p style='color:red;'>❌ Error: " . $stmtUpdate->error . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Membership Plan</title>
  <style>
    body { font-family: 'Poppins', sans-serif; background:#fefdf8; margin:0; }
    .container { max-width:600px; margin:50px auto; background:#fff; padding:30px; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.1); }
    h2 { text-align:center; margin-bottom:20px; }
    label { display:block; margin:10px 0 5px; font-weight:500; }
    input, textarea, select { width:100%; padding:10px; margin-bottom:15px; border:1px solid #ddd; border-radius:8px; }
    button { width:100%; padding:12px; background:#F7C600; color:black; border:none; border-radius:8px; font-weight:bold; cursor:pointer; transition:.3s; }
    button:hover { background:#e0b400; }
    .back { display:inline-block; margin-top:15px; text-decoration:none; color:#2196f3; }
    .back:hover { text-decoration:underline; }
  </style>
</head>
<body>
  <div class="container">
    <h2>Edit Membership Plan</h2>
    <?php echo $message; ?>
    <form method="post">
      <label>Plan Name</label>
      <input type="text" name="plan_name" value="<?php echo htmlspecialchars($plan['plan_name']); ?>" required>

      <label>Description</label>
      <textarea name="description" rows="4" required><?php echo htmlspecialchars($plan['description']); ?></textarea>

      <label>Price ($)</label>
      <input type="number" step="0.01" name="price" value="<?php echo $plan['price']; ?>" required>

      <label>Duration</label>
      <select name="duration">
        <option value="1month" <?php if($plan['duration']=="1month") echo "selected"; ?>>1 Month</option>
        <option value="3months" <?php if($plan['duration']=="3months") echo "selected"; ?>>3 Months</option>
        <option value="6months" <?php if($plan['duration']=="6months") echo "selected"; ?>>6 Months</option>
        <option value="9months" <?php if($plan['duration']=="9months") echo "selected"; ?>>9 Months</option>
        <option value="1year" <?php if($plan['duration']=="1year") echo "selected"; ?>>1 Year</option>
      </select>

      <label>Level</label>
      <select name="level">
        <option value="Beginner" <?php if($plan['level']=="Beginner") echo "selected"; ?>>Beginner</option>
        <option value="Intermediate" <?php if($plan['level']=="Intermediate") echo "selected"; ?>>Intermediate</option>
        <option value="Advance" <?php if($plan['level']=="Advance") echo "selected"; ?>>Advance</option>
        <option value="Professional" <?php if($plan['level']=="Professional") echo "selected"; ?>>Professional</option>
      </select>

      <button type="submit">Update Plan</button>
    </form>
    <a href="admin_plans.php" class="back">⬅ Back to Plans</a>
  </div>
</body>
</html>
