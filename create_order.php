<?php
require_once './dbconnection.php'; // Include your database connection script

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    date_default_timezone_set("Asia/Kolkata");

    // Get the raw POST data and decode it as JSON
    $rawPostData = file_get_contents('php://input');
    $postData = json_decode($rawPostData, true);

    // Check if JSON decoding was successful
    if ($postData === null) {
        echo json_encode(array('success' => false, 'message' => 'Invalid JSON data.'));
        exit;
    }

    // Extract data from the decoded JSON
    $client_txn_id = $postData['client_txn_id'];
    $totalAmount = $postData['amount'];
    $cartItems = $postData['product_info'];
    $status = $postData['status'];
    $address_id = $postData['address_id'];
    $user_id = $postData['user_id'];
    $created_at =  date("d-m-Y");
    // Encode $cartItems as JSON
    $encodedCartItems = json_encode($cartItems);

    // Fetch address details based on the provided $address_id
    $sql = "SELECT * FROM `user_addresses` WHERE `id` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $address_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Address found, fetch the address data
        $addressData = $result->fetch_assoc();
    } else {
        // Address not found, handle the error or provide a default address
        $addressData = null; // You can set a default address or handle the error here
    }

    // Check if the address data is available
    if (!$addressData) {
        echo json_encode(array('success' => false, 'message' => 'Address not found.'));
        exit;
    }

    // Insert data into the `orders` table
    $sql = "INSERT INTO `orders` (`client_txn_id`, `amount`, `product_info`, `status`, `address_id`, `created_at`, `user_id`)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Updated bind_param with the right number of bind variables
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdssisi", $client_txn_id, $totalAmount, $encodedCartItems, $status, $address_id, $created_at, $user_id);

    if ($stmt->execute()) {
        // Order placed successfully, clear the cart cookie
        setcookie('cart_items', '', time() - 3600, '/'); // Clear the cart_items cookie
        // Payment request code
        $key = "eec16523-acc0-45f8-9b07-f2ac9b34fbd1"; // Your API Token
        $post_data = new stdClass();
        $post_data->key = $key;
        $post_data->client_txn_id = $client_txn_id; // Use the same transaction ID
        $post_data->amount = "$totalAmount";
        $post_data->p_info = "product_name";
        $post_data->customer_name = $addressData['name']; // Use customer's name from the address
        $post_data->customer_email = $addressData['email']; // Use customer's email from the address
        $post_data->customer_mobile = $addressData['mobile']; // Use customer's mobile from the address
        $post_data->redirect_url = "https://hathibrand.in/order_check.php"; // Automatically appends parameters
        $post_data->udf1 = "extradata";
        $post_data->udf2 = "extradata";
        $post_data->udf3 = "extradata";

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.ekqr.in/api/create_order',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($post_data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($response, true);
        if ($result['status'] == true) {
            echo $result['data']["payment_url"];
            exit();
        }

        echo '<div class="alert alert-danger">' . $result['msg'] . '</div>';
    } else {
        echo json_encode(array('success' => false, 'message' => 'Error placing the order.'));
        exit;
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Invalid request method.'));
    exit;
}
?>
