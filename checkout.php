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
    $cartItemsJson = $_POST['cart_items_json']; // Assuming the data is sent as JSON string

    // Check if the cart_items JSON data is provided
    if (!isset($cartItemsJson) || empty($cartItemsJson)) {
        echo json_encode(array('success' => false, 'message' => 'Cart data is missing.'));
        exit;
    }

    // Decode the JSON data sent from JavaScript
    $cartItems = json_decode($cartItemsJson, true);

    // Check if the cart is empty
    if (empty($cartItems)) {
        echo json_encode(array('success' => false, 'message' => 'Cart is empty.'));
        exit;
    }

    // Retrieve the client transaction ID and total amount from the POST data
    $client_txn_id = $_POST['client_txn_id'];
    $totalAmount = $_POST['total_amount'];

    // Set the initial status
    $status = 'Pending'; // You can set the initial status here

    // Get the user's address ID
    $address_id = ($addressData !== null) ? $addressData['id'] : null;

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
?>
