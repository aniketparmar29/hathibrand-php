<?php
// Get the cart items data sent from the client
$cartItems = json_decode(file_get_contents('php://input'), true);

// Save the cart items data in cookies
setcookie('cartItems', json_encode($cartItems), time() + 7 * 24 * 60 * 60, '/'); // Expires in 7 days

// Return a success response
$response = [
    'success' => true,
    'message' => 'Cart items data updated in cookies successfully!',
];

header('Content-Type: application/json');
echo json_encode($response);
?>
