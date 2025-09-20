<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
  header("Location: login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>User Dashboard - LiftKings</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Poppins:wght@400;500&display=swap');

    html {
      scroll-behavior: smooth;
    }

    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: #fefdf8;
      color: #000;
    }

    /* Header */
    .site-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 40px;
      background: #fff;
      border-bottom: 3px solid #F7C600;
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .logo {
      font-family: 'Montserrat', sans-serif;
      font-size: 2rem;
      margin: 0;
      text-transform: uppercase;
    }

    .logo i {
      font-size: 30px;
    }

    .logo .lift {
      color: #000;
      font-size: 32px;
    }

    .logo .kings {
      color: #F7C600;
      font-size: 33px;
    }

    .logo-icon {
      width: 50px;
      height: 40px;
    }


    nav a {
      margin: 0 15px;
      text-decoration: none;
      color: #000;
      font-weight: 500;
      transition: 0.3s;
    }

    nav a:hover {
      color: #F7C600;
    }

    /* Hero Section */
    .hero {
      text-align: center;
      padding: 60px 20px;
      background: linear-gradient(135deg, #fff7d1, #fff);
    }

    .hero h1 {
      font-size: 2.2rem;
      margin-bottom: 10px;
    }

    .hero p {
      font-size: 1.1rem;
      color: #555;
    }

    /* Cards Section */
    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 25px;
      max-width: 1100px;
      margin: 50px auto;
      padding: 0 20px;
    }

    .card {
      background: #fff;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      text-align: center;
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card i {
      font-size: 2rem;
      color: #F7C600;
      margin-bottom: 15px;
    }

    .card h3 {
      margin-bottom: 10px;
    }

    .card p {
      font-size: 0.95rem;
      color: #555;
    }

    /* Membership CTA */
    .membership {
      text-align: center;
      padding: 60px 20px;
      background: #fffbea;
      border-top: 2px solid #F7C600;
    }

    .membership h2 {
      font-size: 2rem;
      margin-bottom: 20px;
    }

    .membership a {
      display: inline-block;
      padding: 14px 30px;
      background: #F7C600;
      color: #000;
      font-weight: bold;
      border-radius: 10px;
      text-decoration: none;
      transition: 0.3s;
    }

    .membership a:hover {
      background: #e0b400;
    }

    /* Footer */
    footer {
      text-align: center;
      padding: 20px;
      background: #fff;
      border-top: 2px solid #F7C600;
      color: #555;
      font-size: 0.9rem;
      margin-top: 40px;
      /* Why Choose Us */
    }

    .why-us {
      text-align: center;
      padding: 60px 20px;
      background: #fffbea;
      border-top: 2px solid #F7C600;
    }

    .why-us h2 {
      font-size: 2rem;
      margin-bottom: 40px;
    }

    .why-us .highlight {
      color: #F7C600;
    }

    .reasons {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 25px;
      max-width: 1000px;
      margin: auto;
    }

    .reason {
      background: #fff;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .reason i {
      font-size: 2rem;
      color: #F7C600;
      margin-bottom: 15px;
    }

    .reason h3 {
      margin-bottom: 10px;
    }

    /* Testimonials */
    .testimonials {
      text-align: center;
      padding: 60px 20px;
      background: #fff;
    }

    .testimonials h2 {
      font-size: 2rem;
      margin-bottom: 40px;
    }

    .testimonial-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 25px;
      max-width: 1000px;
      margin: auto;
    }

    .testimonial {
      background: #fffbea;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      font-style: italic;
    }

    .testimonial h4 {
      margin-top: 15px;
      font-weight: bold;
      color: #000;
    }

    /* Contact Section */
    .contact {
      padding: 60px 20px;
      background: #fffbea;
      border-top: 2px solid #F7C600;
      text-align: center;
    }

    .contact h2 {
      font-size: 2rem;
      margin-bottom: 15px;
    }

    .contact-sub {
      color: #555;
      margin-bottom: 40px;
    }

    .contact-container {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 40px;
      max-width: 1000px;
      margin: auto;
      align-items: start;
    }

    .contact-form {
      background: #fff;
      padding: 25px;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      text-align: left;
    }

    .contact-form label {
      font-weight: bold;
      display: block;
      margin: 10px 0 5px;
      color: #000;
    }

    .contact-form input,
    .contact-form textarea {
      width: 95%;
      padding: 12px;
      border: 1px solid #ddd;
      border-radius: 8px;
      margin-bottom: 15px;
      font-size: 14px;
    }

    .contact-form input:focus,
    .contact-form textarea:focus {
      border-color: #F7C600;
      outline: none;
      box-shadow: 0px 0px 6px rgba(247, 198, 0, 0.4);
    }

    .contact-form button {
      width: 100%;
      padding: 12px;
      background: #F7C600;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      color: #000;
      cursor: pointer;
      transition: 0.3s;
    }

    .contact-form button:hover {
      background: #e0b400;
    }

    .contact-info {
      text-align: left;
      padding: 15px;
    }

    .contact-info h3 {
      margin-bottom: 15px;
    }

    .contact-info p {
      margin: 10px 0;
      color: #333;
    }

    .contact-info i {
      color: #F7C600;
      margin-right: 10px;
    }
  </style>
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
    <nav>
      <a href="index.php">Home</a>
      <a href="#contact">Contact Us</a>
      <a href="membership.php">Membership Plans</a>
      <a href="logout.php">Logout</a>
    </nav>
  </header>

  <section class="hero">
    <h1>Welcome, <?php echo $_SESSION['name']; ?> 👋</h1>
    <p>Explore our features, learn more about us, and upgrade to membership for exclusive benefits!</p>
  </section>

  <section class="cards">
    <div class="card">
      <i class="fa fa-dumbbell"></i>
      <h3>Modern Equipment</h3>
      <p>Access the latest machines and tools for strength and cardio training.</p>
    </div>

    <div class="card">
      <i class="fa fa-user-tie"></i>
      <h3>Expert Trainers</h3>
      <p>Our certified trainers guide you with personalized workout programs.</p>
    </div>

    <div class="card">
      <i class="fa fa-apple-alt"></i>
      <h3>Healthy Lifestyle</h3>
      <p>Get nutrition advice and tips to stay fit and strong every day.</p>
    </div>

    <div class="card">
      <i class="fa fa-users"></i>
      <h3>Community</h3>
      <p>Be part of a like-minded community motivating each other to succeed.</p>
    </div>

    <div class="card">
      <i class="fa fa-clock"></i>
      <h3>Flexible Timing</h3>
      <p>Workout at your convenience with flexible hours to match your schedule.</p>
    </div>

    <div class="card">
      <i class="fa fa-wallet"></i>
      <h3>Affordable Plans</h3>
      <p>Choose from a variety of budget-friendly plans without compromising quality.</p>
    </div>
  </section>


  <section class="membership">
    <h2>Ready to Become a Member?</h2>
    <a href="membership.php">View Membership Plans</a>
  </section>

  <!-- Why Choose Us Section -->
  <section class="why-us">
    <h2>Why Choose <span class="highlight">LiftKings</span>?</h2>
    <div class="reasons">
      <div class="reason">
        <i class="fa fa-clock"></i>
        <h3>Open 24/7</h3>
        <p>Workout at your convenience with round-the-clock access.</p>
      </div>
      <div class="reason">
        <i class="fa fa-user-tie"></i>
        <h3>Certified Trainers</h3>
        <p>Guidance from experienced and certified fitness experts.</p>
      </div>
      <div class="reason">
        <i class="fa fa-heartbeat"></i>
        <h3>Healthy Lifestyle</h3>
        <p>Personalized diet and workout tips for your fitness journey.</p>
      </div>
    </div>
  </section>

  <!-- Testimonials Section -->
  <section class="testimonials">
    <h2>What Our Members Say</h2>
    <div class="testimonial-cards">
      <div class="testimonial">
        <p>"LiftKings has completely transformed my fitness journey. The trainers are amazing!"</p>
        <h4>- Sarah J.</h4>
      </div>
      <div class="testimonial">
        <p>"The equipment is top-notch and I love the positive community vibes here."</p>
        <h4>- Mark T.</h4>
      </div>
      <div class="testimonial">
        <p>"Affordable plans and the best gym experience I've had so far."</p>
        <h4>- Priya R.</h4>
      </div>
    </div>
  </section>

  <!-- Contact Us Section -->
  <section class="contact" id="contact">
    <h2>Contact Us</h2>
    <p class="contact-sub">We’d love to hear from you! Reach out with any questions or suggestions.</p>

    <div class="contact-container">
      <!-- Contact Form -->
      <form class="contact-form" method="post" action="send_message.php">
        <label for="name">Your Name</label>
        <input type="text" id="name" name="name" placeholder="Enter your name" required>

        <label for="email">Your Email</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>

        <label for="message">Message</label>
        <textarea id="message" name="message" rows="5" placeholder="Write your message..." required></textarea>

        <button type="submit">Send Message</button>
      </form>

      <!-- Contact Info -->
      <div class="contact-info">
        <h3>Get in Touch</h3>
        <p><i class="fa fa-map-marker-alt"></i> 123 Fitness Street, Pune, India</p>
        <p><i class="fa fa-phone"></i> +91 98765 43210</p>
        <p><i class="fa fa-envelope"></i> support@liftkings.com</p>
      </div>
      
    </div>
  </section>



  <footer>
    &copy; <?php echo date("Y"); ?> LiftKings Gym. All rights reserved.
  </footer>

</body>

</html>