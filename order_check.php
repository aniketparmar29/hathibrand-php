<?php
error_reporting(E_ERROR | E_PARSE);

// Include your database connection script (e.g., dbconnection.php)
require_once 'dbconnection.php';

$echo = "";

if (isset($_GET['client_txn_id']) && isset($_GET['txn_id'])) {
    $key = "eec16523-acc0-45f8-9b07-f2ac9b34fbd1"; // Your API Token from https://merchant.upigateway.com/user/api_credentials

    // Prepare data for API request
    $post_data = new stdClass();
    $post_data->key = $key;
    $post_data->client_txn_id = $_GET['client_txn_id'];
    
    // Get today's date in dd-mm-yyyy format and add it to the post data
    $post_data->txn_date = date("d-m-Y");

    // Initialize cURL
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.ekqr.in/api/check_order_status',
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

    // Execute the API request
    $response = curl_exec($curl);

    if ($response === false) {
        $echo = '<div class="alert alert-danger">cURL Error: ' . curl_error($curl) . '</div>';
    } else {
        // Parse the API response
        $result = json_decode($response, true);

        if ($result['status'] == true) {
            // Transaction Status could be 'created', 'scanning', 'success', 'failure'
            if ($result['data']['status'] == 'success') {
                // Update the order status in the database
                $new_status = 'Success'; // Set the new status
                $client_txn_id = $_GET['client_txn_id']; // Define client_txn_id

                // Prepare and execute the SQL query to update the order status
                $sql = "UPDATE orders SET status = ? WHERE client_txn_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $new_status, $client_txn_id);

                if ($stmt->execute()) {
                    // Order status updated successfully
                    $echo = '<div class="alert alert-success">Transaction Status: Success. Order status updated.</div>';
                } else {
                    // Error occurred while updating the order status
                    $echo = '<div class="alert alert-danger">Transaction Status: Success. Error updating order status: ' . $stmt->error . '</div>';
                }

                // Close the database connection
                $stmt->close();
            } else {
                $echo = '<div class="alert alert-info">Transaction Status: ' . $result['data']['status'] . '</div>';
            }

            // Additional information about the transaction
            $txn_data = $result['data'];
        } else {
            $echo = '<div class="alert alert-danger">API Error: ' . $result['msg'] . '</div>';
        }
    }

    // Close the cURL handle
    curl_close($curl);
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Check</title>
    <!-- Include Tailwind CSS stylesheet -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
<?php include './components/Navbar.php'?>

    <!-- Container with Tailwind CSS styles -->
    <div class="flex justify-center items-center min-h-screen">
    <div class="w-full max-w-md p-4">
        <h1 class="text-2xl font-bold mb-4">Order Status Check</h1>
        <?php if (isset($echo)) : ?>
            <div class="alert <?php echo $txn_data && ($txn_data['status'] == 'success' || $txn_data['status'] == 'failure') ? 'alert-success' : 'alert-info'; ?>">
                <?php echo $echo; ?>
            </div>
        <?php endif; ?>

        <!-- Display the "My Orders" anchor tag if status is success or failure -->
            <a href="my_order.php" class="mt-4 inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">My Orders</a>
    </div>
    </div>
    <?php include './components/Footer.php'?>

</body>
</html>

