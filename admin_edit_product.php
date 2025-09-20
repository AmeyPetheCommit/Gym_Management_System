<?php
session_start();
include './config.php';

// Sirf admin access kare
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Get product ID
if (!isset($_GET['id'])) {
    header("Location: admin_products.php");
    exit;
}
$product_id = intval($_GET['id']);

// Fetch product details
$sql = "SELECT * FROM store_products WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die("Product not found!");
}

$message = "";

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];

    // Image handling
    $image = $product['image']; // old image
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . time() . "_" . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image = basename($target_file);
        }
    }

    $sql = "UPDATE store_products 
            SET name=?, description=?, price=?, stock=?, category=?, image=? 
            WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdissi", $name, $description, $price, $stock, $category, $image, $product_id);

    if ($stmt->execute()) {
        $message = "<p style='color:green;'>✅ Product updated successfully!</p>";
        // Refresh product data
        $stmt = $conn->prepare("SELECT * FROM store_products WHERE id=?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();
    } else {
        $message = "<p style='color:red;'>❌ Error: " . $stmt->error . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Product - LiftKings</title>
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
    .preview { text-align:center; margin-bottom:15px; }
    .preview img { width:120px; height:120px; object-fit:cover; border-radius:8px; border:1px solid #ccc; }
  </style>
</head>
<body>
  <div class="container">
    <h2><i class="fa-solid fa-pen-to-square"></i> Edit Product</h2>
    <?php echo $message; ?>
    <form method="post" enctype="multipart/form-data">
      <label>Product Name</label>
      <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

      <label>Description</label>
      <textarea name="description" rows="4" required><?php echo htmlspecialchars($product['description']); ?></textarea>

      <label>Price (₹)</label>
      <input type="number" name="price" step="0.01" value="<?php echo $product['price']; ?>" required>

      <label>Stock Quantity</label>
      <input type="number" name="stock" value="<?php echo $product['stock']; ?>" required>

      <label>Category</label>
      <select name="category">
        <option value="protein" <?php if($product['category']=="protein") echo "selected"; ?>>Protein</option>
        <option value="vitamin" <?php if($product['category']=="vitamin") echo "selected"; ?>>Vitamin</option>
        <option value="preworkout" <?php if($product['category']=="preworkout") echo "selected"; ?>>Pre-Workout</option>
        <option value="snacks" <?php if($product['category']=="snacks") echo "selected"; ?>>Snacks</option>
        <option value="others" <?php if($product['category']=="others") echo "selected"; ?>>Others</option>
      </select>

      <label>Current Image</label>
      <div class="preview">
        <?php if($product['image']): ?>
          <img src="uploads/<?php echo $product['image']; ?>" alt="Current Image">
        <?php else: ?>
          <img src="../uploads/default.png" alt="No Image">
        <?php endif; ?>
      </div>

      <label>Upload New Image (optional)</label>
      <input type="file" name="image" accept="image/*">

      <button type="submit">Update Product</button>
    </form>
  </div>
</body>
</html>
