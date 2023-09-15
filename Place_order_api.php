<?php
require_once './dbconnection.php'; // Include your database connection script

// Initialize the response array
$response = array();

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON data from the request body
    $json_data = file_get_contents("php://input");
    
    // Decode the JSON data
    $data = json_decode($json_data, true);
    
    // Check if the required fields are present in the JSON data
    if (isset($data['address']) && isset($data['product']) && isset($data['total_amount'])) {
        $address = $data['address'];
        $product = $data['product'];
        $total_amount = $data['total_amount'];
        
        // Assuming you have a user authentication system and have a user ID in session
        $user_id = $_COOKIE['user_id'];
        
        // Prepare and execute the SQL query to insert the order
        $sql = "INSERT INTO `orders`(`Address`, `Product`, `date`, `payment_status`, `total_amount`, `user_id`) 
                VALUES (?, ?, NOW(), 'Pending', ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        // Serialize the product data to store in the database
        $product_data = json_encode($product);
        
        if ($stmt->execute([$address, $product_data, $total_amount, $user_id])) {
            // Order placed successfully
            $response['status'] = 'success';
            $response['message'] = 'Order placed successfully!';
        } else {
            // Failed to place the order
            $response['status'] = 'error';
            $response['message'] = 'Error placing the order.';
        }
    } else {
        // Invalid JSON data
        $response['status'] = 'error';
        $response['message'] = 'Invalid JSON data.';
    }
} else {
    // Invalid request method
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method.';
}

// Set the Content-Type header to indicate JSON response
header('Content-Type: application/json');

// Output the response as JSON
echo json_encode($response);
?>
