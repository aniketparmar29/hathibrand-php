<?php
// getcartitems.php
session_start();

// Get the cart items from the session
$cartItems = $_SESSION['cartItems'] ?? [];

// Send the cart items as a JSON response
header('Content-Type: application/json');
echo json_encode($cartItems);
?>
