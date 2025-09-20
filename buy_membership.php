<?php
session_start();
include 'config.php';
require 'vendor/autoload.php'; // Stripe installed via composer

// Ensure only logged in users (not already members) can buy
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit;
}

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY); // ⚠️ Replace with your Stripe Secret Key

// Step 1: Get Plan ID
if (!isset($_POST['plan_id'])) {
    die("No plan selected!");
}
$plan_id = intval($_POST['plan_id']);

// Step 2: Fetch Plan Details
$plan_res = $conn->query("SELECT * FROM membership_plans WHERE id=$plan_id");
if ($plan_res->num_rows == 0) {
    die("Invalid Plan Selected!");
}
$plan = $plan_res->fetch_assoc();

// Step 3: Create Stripe Checkout Session
$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => [[
        'price_data' => [
            'currency' => 'usd',
            'product_data' => ['name' => $plan['plan_name']],
            'unit_amount' => $plan['price'] * 100, // convert to cents
        ],
        'quantity' => 1,
    ]],
    'mode' => 'payment',
    'success_url' => 'http://localhost/Gym-Management/membership_success.php?plan_id='.$plan_id.'&session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => 'http://localhost/Gym-Management/membership_cancel.php',
]);

// Step 4: Redirect to Stripe Checkout
header("Location: " . $session->url);
exit;
?>
