<?php
session_start();
include 'config.php';
require 'vendor/autoload.php'; // stripe-php autoload

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'member') {
    header("Location: login.php");
    exit;
}

// Calculate total from cart
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p style='color:red;text-align:center;'>Your cart is empty!</p>";
    exit;
}

include 'config.php';

// Fetch products in cart
$total = 0;
$ids = implode(",", array_keys($_SESSION['cart']));
$sql = "SELECT * FROM store_products WHERE id IN ($ids)";
$result = $conn->query($sql);

$line_items = [];

while ($row = $result->fetch_assoc()) {
    $qty = $_SESSION['cart'][$row['id']];
    $price = $row['price'] * 100; // convert to paise
    $total += $row['price'] * $qty;

    $line_items[] = [
        'price_data' => [
            'currency' => 'inr',
            'product_data' => [
                'name' => $row['name'],
            ],
            'unit_amount' => $price,
        ],
        'quantity' => $qty,
    ];
}

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

$session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => $line_items,
    'mode' => 'payment',
    'success_url' => 'http://liftkings.great-site.net/success.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url'  => 'http://localhost/liftkings.great-site.net/cart.php',
]);

header("Location: " . $session->url);
exit;
?>
