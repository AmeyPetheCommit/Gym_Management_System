<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = trim($_POST['name']);
    $phone    = trim($_POST['phone']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($check->num_rows > 0) {
        $error = "Email already registered. Try another.";
    } else {
        $sql = "INSERT INTO users (name, phone, email, password, role) 
                VALUES ('$name', '$phone', '$email', '$hashedPassword', 'user')";
        if ($conn->query($sql)) {
            $success = "Registration successful! ";
            header("Location: login.php");
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - LiftKings</title>
  <link rel="stylesheet" href="style.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Poppins:wght@400;500&display=swap');

    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #fff9e6, #fff);
      color: #000;
    }

    /* Header */
    .site-header {
      text-align: center;
      padding: 25px 15px;
      background: #fff;
      border-bottom: 3px solid #F7C600;
    }

    .logo {
      font-family: 'Montserrat', sans-serif;
      font-size: 3rem;
      margin: 0;
      letter-spacing: 2px;
      text-transform: uppercase;
    }

    .logo .lift {
      color: #000;
    }

    .logo .kings {
      color: #F7C600;
    }

    /* Form Card */
    .form-container {
      max-width: 520px;
      width: 100%;
      margin: 60px auto;
      background: #fff;
      padding: 35px 30px;
      border-radius: 15px;
      box-shadow: 0px 8px 25px rgba(0, 0, 0, 0.1);
      border-top: 6px solid #F7C600;
      animation: fadeIn 0.6s ease-in-out;
    }

    .form-container h2 {
      text-align: center;
      margin-bottom: 25px;
      font-size: 1.6rem;
      font-weight: 600;
      color: #000;
    }

    label {
      display: block;
      margin: 10px 0 5px;
      font-weight: 500;
      color: #000;
    }

    .input-group {
      position: relative;
    }

    .input-group input {
      width: 90%;
      padding: 12px 40px 12px 15px;
      border: 1px solid #ddd;
      border-radius: 8px;
      margin-bottom: 18px;
      font-size: 14px;
      transition: 0.3s;
    }

    .input-group input:focus {
      border-color: #F7C600;
      box-shadow: 0px 0px 6px rgba(247, 198, 0, 0.5);
      outline: none;
    }

    .input-group i {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #888;
    }

    /* Button */
    button {
      width: 100%;
      padding: 12px;
      background: #F7C600;
      border: none;
      border-radius: 8px;
      color: #000;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    button:hover {
      background: #e0b400;
      transform: scale(1.02);
    }

    /* Text Links */
    .form-container p {
      text-align: center;
      color: #555;
      margin-top: 20px;
      font-size: 14px;
    }

    a {
      color: #F7C600;
      font-weight: bold;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }

    /* Messages */
    .message {
      margin-top: 15px;
      text-align: center;
      font-weight: bold;
    }

    .error {
      color: red;
    }

    .success {
      color: green;
    }

    /* Animation */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <header class="site-header">
    <h1 class="logo">
      <span class="lift">Lift</span><span class="kings">Kings</span>
    </h1>
  </header>

  <section class="form-container">
    <h2>Create Your Account</h2>
    <form method="post">
      <label>Full Name</label>
      <div class="input-group">
        <input type="text" name="name" placeholder="Enter your full name" required>
        <i class="fa fa-user"></i>
      </div>

      <label>Phone Number</label>
      <div class="input-group">
        <input type="text" name="phone" placeholder="Enter your phone number" required>
        <i class="fa fa-phone"></i>
      </div>

      <label>Email</label>
      <div class="input-group">
        <input type="email" name="email" placeholder="Enter your email" required>
        <i class="fa fa-envelope"></i>
      </div>

      <label>Password</label>
      <div class="input-group">
        <input type="password" name="password" placeholder="Enter a password" required>
        <i class="fa fa-lock"></i>
      </div>

      <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>

    <?php
      if (!empty($error))   echo "<div class='message error'>$error</div>";
      if (!empty($success)) echo "<div class='message success'>$success</div>";
    ?>
  </section>
</body>
</html>
