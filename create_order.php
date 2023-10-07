<?php
require_once './dbconnection.php'; // Include your database connection script

// Check if the user's ID (you may need to adjust how you retrieve this)
$user_id = $_COOKIE['user_id'];

// Check if the user has an existing address
$sql = "SELECT * FROM `user_addresses` WHERE `user_id` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User has an existing address, fetch it
    $addressData = $result->fetch_assoc();
} else {
    // User does not have an address, set $addressData to null
    $addressData = null;
}

$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle the form submission
    $cartItemsCookie = $_COOKIE['cart_items'];

    // Check if the cart_items cookie is set
    if (!isset($cartItemsCookie)) {
        echo json_encode(array('success' => false, 'message' => 'Cart is empty or missing cart_items cookie.'));
        exit;
    }

    // Retrieve cart items from the cookie
    $cartItems = json_decode($cartItemsCookie, true);

    // Check if the cart is empty
    if (empty($cartItems)) {
        echo json_encode(array('success' => false, 'message' => 'Cart is empty.'));
        exit;
    }

    // Prepare the order data
    $client_txn_id = generateRandomClientId();
    $totalAmount = calculateTotalAmount($cartItems);
    $status = 'Pending'; // You can set the initial status here
    $address_id = $addressData['id']; // Replace with the user's address ID

    // Insert the order into the database
    $sql = "INSERT INTO `orders` (`client_txn_id`, `amount`, `product_info`, `status`, `address_id`, `created_at`, `user_id`)
            VALUES (?, ?, ?, ?, ?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        // Handle the prepare error
        echo json_encode(array('success' => false, 'message' => 'Error preparing the SQL statement.'));
        exit;
    }
    
    $stmt->bind_param("sdssii", $client_txn_id, $totalAmount, json_encode($cartItems), $status, $address_id, $user_id);

    if ($stmt->execute()) {
        // Order placed successfully, clear the cart cookie
        setcookie('cart_items', '', time() - 3600, '/'); // Clear the cart_items cookie
        echo json_encode(array('success' => true, 'message' => 'Order placed successfully.'));
        exit;
    } else {
        echo json_encode(array('success' => false, 'message' => 'Error placing the order.'));
        exit;
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Invalid request method.'));
    exit;
}

// Function to generate a random client transaction ID
function generateRandomClientId() {
    return "client_txn_" . uniqid();
}

// Function to calculate the total order amount
function calculateTotalAmount($cart) {
    $totalAmount = 0;
    foreach ($cart as $item) {
        $totalAmount += $item['productPrice'] * $item['quantity'];
    }
    return $totalAmount;
}
?>
