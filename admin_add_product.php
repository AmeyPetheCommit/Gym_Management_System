<?php
session_start();
include 'config.php';

// Ensure only admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $category = $_POST['category'];

    // Handle image upload
    $image = null;
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . time() . "_" . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = basename($target_file);
        }
    }

    // Insert product into DB
    $sql = "INSERT INTO store_products (name, description, price, stock, category, image) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdiss", $name, $description, $price, $stock, $category, $image);

    if ($stmt->execute()) {
        $message = "<p style='color:green;'>✅ Product added successfully!</p>";
    } else {
        $message = "<p style='color:red;'>❌ Error: " . $stmt->error . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Product - LiftKings</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body { font-family: 'Poppins', sans-serif; background:#fefdf8; margin:0; }
    .container { max-width:600px; margin:50px auto; background:#fff; padding:30px; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.1); }
    h2 { text-align:center; margin-bottom:20px; }
    label { display:block; margin:10px 0 5px; font-weight:500; }
    input, textarea, select { width:100%; padding:10px; margin-bottom:15px; border:1px solid #ddd; border-radius:8px; }
    button { width:100%; padding:12px; background:#F7C600; border:none; border-radius:8px; font-weight:bold; cursor:pointer; transition:.3s; }
    button:hover { background:#e0b400; }
    .message { text-align:center; margin-top:15px; }
  </style>
</head>
<body>
  <div class="container">
    <h2><i class="fa-solid fa-box"></i> Add New Product</h2>
    <?php echo $message; ?>
    <form method="post" enctype="multipart/form-data">
      <label>Product Name</label>
      <input type="text" name="name" required>

      <label>Description</label>
      <textarea name="description" rows="4" required></textarea>

      <label>Price (₹)</label>
      <input type="number" name="price" step="0.01" required>

      <label>Stock Quantity</label>
      <input type="number" name="stock" required>

      <label>Category</label>
      <select name="category">
        <option value="protein">Protein</option>
        <option value="vitamin">Vitamin</option>
        <option value="preworkout">Pre-Workout</option>
        <option value="snacks">Snacks</option>
        <option value="others">Others</option>
      </select>

      <label>Upload Image</label>
      <input type="file" name="image" accept="image/*">

      <button type="submit">Add Product</button>
    </form>
  </div>
</body>
</html>
