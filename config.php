<?php
$host = "localhost";
$user = "root";      // your MySQL username
$pass = "";          // your MySQL password
$db   = "gym_db";
define("STRIPE_SECRET_KEY", "sk_test_51RqC7wFAURqMFXjZPfLDZtf9TVEgRYM0b5DFt1gPuN2vy0JTtKh9MjBU1Qvkiogk4hMgBj5ttkg8UAoB6CJ8TiAy00wpBsZ0Tz");
define("STRIPE_PUBLISHABLE_KEY", "pk_test_51RqC7wFAURqMFXjZteL0IM9YJ91HVTgwVtO5YRsDx7kTPgfQ0fgyxUxnd4hTEhqk8FaVxwmcADMKk9KkzHYT7VX100bfbpmxLR");


$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
