<?php
require_once '../dbconnection.php'; // Include your database connection script

if (isset($_GET['orderId'])) {
    $orderId = $_GET['orderId'];

    // Query to fetch order details by orderId
    $sql = "SELECT o.*, ua.*
            FROM `orders` o
            JOIN `user_addresses` ua ON o.`address_id` = ua.`id`
            WHERE o.`id` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Parse the product information JSON string
        $productInfo = json_decode($row['product_info'], true);

        // Create the invoice HTML content
        $invoiceContent = "
            <html>
            <head>
                <style>
                    /* Define your CSS styles for the invoice here */
                    .invoice {
                        font-family: Arial, sans-serif;
                        width: 80%;
                        margin: 0 auto;
                    }
                    .header {
                        text-align: center;
                    }
                    .logo {
                        max-width: 150px;
                    }
                    .details {
                        margin-top: 20px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 20px;
                    }
                    table, th, td {
                        border: 1px solid #ddd;
                    }
                    th, td {
                        padding: 8px;
                        text-align: left;
                    }
                    .total {
                        margin-top: 20px;
                        text-align: right;
                    }
                </style>
            </head>
            <body>
                <div class='invoice'>
                    <div class='header'>
                        <img src='../assets/Logo/Favicon.ico' alt='Your Logo' class='logo'>
                        <h2>Invoice</h2>
                    </div>
                    <div class='details'>
                        <p>Order ID: {$row['id']}</p>
                        <!-- Add more order details here -->
                        
                        <p>User Address:</p>
                        <p>Name: {$row['name']}</p>
                        <p>Mobile: {$row['mobile']}</p>
                        <p>Email: {$row['email']}</p>
                        <p>Alternative Mobile: {$row['alt_mobile']}</p>
                        <p>District: {$row['district']}</p>
                        <p>Taluka: {$row['taluka']}</p>
                        <p>Village: {$row['village']}</p>
                        <p>Address: {$row['address']}</p>
                        <p>Pincode: {$row['pincode']}</p>
                        
                        <table>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                            </tr>";

        // Iterate through the product information and include it in the invoice
        foreach ($productInfo as $product) {
            $productName = $product['productName'];
            $productPrice = $product['productPrice'];
            $productQuantity = $product['quantity'];

            $invoiceContent .= "
                            <tr>
                                <td>{$productName}</td>
                                <td>{$productPrice}</td>
                                <td>{$productQuantity}</td>
                            </tr>";
        }

        $invoiceContent .= "
                        </table>
                        <div class='total'>
                            <p>Total: {$row['amount']}</p>
                        </div>
                    </div>
                </div>
                <button onclick='printInvoice()'>Print</button> <!-- Print button -->
                <script>
                    function printInvoice() {
                        window.print(); // Trigger the browser's print functionality
                    }
                </script>
            </body>
            </html>
        ";

        echo $invoiceContent;
    } else {
        echo "Order not found.";
    }
} else {
    echo "Invalid request.";
}
?>
