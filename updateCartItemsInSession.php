<?php
// updateCartItemsInSession.php

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get the cart items data sent from the client
$cartItems = json_decode(file_get_contents('php://input'), true);

// Save the cart items data in the session
$_SESSION['cartItems'] = json_encode($cartItems);

// Return a success response
$response = [
    'success' => true,
    'message' => 'Cart items data updated in session successfully!',
];

header('Content-Type: application/json');
echo json_encode($response);
