<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");

    if ($result && $result->num_rows == 1) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['user'] = $row['email'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['role'] = $row['role'];

            if ($row['role'] == 'admin') {
                header("Location: admin_dashboard.php");
            } elseif ($row['role'] == 'member') {
                header("Location: member_dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Invalid email or password!";
        }
    } else {
        $error = "Invalid email or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - LiftKings</title>
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
  font-size: 2.5rem;
  margin: 0;
  letter-spacing: 2px;
  text-transform: uppercase;
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 8px;
}

.logo .lift {
  color: #000;
  display: inline-flex;
  align-items: center;
}

.logo .kings {
  color: #F7C600;
  display: inline-flex;
  align-items: center;
}

.logo-icon {
  width: 66px;
  height: 102px;
  margin: 0 4px 10px 4px;
}

.logo-dumbbell {
  margin: 0 4px;
  color: #F7C600;
  font-size: 40px;
}

    /* Form Container */
    .form-container {
      max-width: 520px;
      width: 100%;
      margin: 70px auto;
      background: #fff;
      padding: 45px 40px;
      border-radius: 15px;
      box-shadow: 0px 10px 30px rgba(0,0,0,0.12);
      border-top: 6px solid #F7C600;
      animation: fadeIn 0.6s ease-in-out;
    }

    .form-container h2 {
      text-align: center;
      margin-bottom: 30px;
      font-size: 1.8rem;
      font-weight: 600;
      color: #000;
    }

    .form-container .lift{
        color: #000;
    }
    .form-container .kings{
        color: #F7C600;
    }
    label {
      display: block;
      margin: 10px 0 5px;
      font-weight: 500;
      color: #000;
    }

    .input-group {
      position: relative;
      margin-bottom: 22px;
    }

    .input-group input {
      width: 90%;
      padding: 14px 50px 14px 18px;
      border: 1px solid #ddd;
      border-radius: 10px;
      font-size: 15px;
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
      padding: 15px;
      background: #F7C600;
      border: none;
      border-radius: 10px;
      color: #000;
      font-size: 18px;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    button:hover {
      background: #e0b400;
      transform: scale(1.02);
    }

    /* Links */
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
    a:hover { text-decoration: underline; }

    /* Messages */
    .message {
      margin-top: 15px;
      text-align: center;
      font-weight: bold;
    }
    .error { color: red; }

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
  <span class="lift">
    Lif<img src="free_icon_1.svg" alt="Icon" class="logo-icon">
  </span>
  <span class="kings">
    K<i class="fa-solid fa-dumbbell fa-rotate-90 logo-dumbbell"></i>ngs
  </span>
</h1>

  </header>

  <section class="form-container">
    <h2>Login to <span class="lift">LIFT</span><span class="kings">KINGS</span></h2>
    <form method="post">
      <label>Email</label>
      <div class="input-group">
        <input type="email" name="email" placeholder="Enter your email" required>
        <i class="fa fa-envelope"></i>
      </div>

      <label>Password</label>
      <div class="input-group">
        <input type="password" name="password" placeholder="Enter your password" required>
        <i class="fa fa-lock"></i>
      </div>

      <button type="submit">Login</button>
    </form>

    <p>Don’t have an account? <a href="register.php">Create one</a></p>

    <?php if (!empty($error)) echo "<div class='message error'>$error</div>"; ?>
  </section>
</body>
</html>
