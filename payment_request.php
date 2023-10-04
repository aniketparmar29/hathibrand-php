<?php
if (isset($_POST['payment'])) {
    $key = "eec16523-acc0-45f8-9b07-f2ac9b34fbd1"; // Your API Token https://merchant.upigateway.com/user/api_credentials

    // Prepare the payment request data
    $post_data = new stdClass();
    $post_data->key = $key;
    $post_data->client_txn_id = (string) rand(100000, 999999); // You can use this field to store order ID
    $post_data->amount = $_POST['txnAmount'];
    $post_data->p_info = "product_name"; // Replace with the actual product information
    $post_data->customer_name = $_POST['customerName'];
    $post_data->customer_email = $_POST['customerEmail'];
    $post_data->customer_mobile = $_POST['customerMobile'];
    $post_data->redirect_url = "https://hathibrand.in/"; // Replace with your redirect URL
    $post_data->udf1 = "extradata"; // Replace with additional data if needed
    $post_data->udf2 = "extradata"; // Replace with additional data if needed
    $post_data->udf3 = "extradata"; // Replace with additional data if needed

    // Send the payment request
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://merchant.upigateway.com/api/create_order',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($post_data),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);

    $result = json_decode($response, true);
    if ($result['status'] == true) {
        // Redirect the user to the payment page
        echo '<script>location.href="' . $result['data']['payment_url'] . '"</script>';
        exit();
    }

    echo '<div class="alert alert-danger">' . $result['msg'] . '</div>';
}
?>
