<?php
namespace Dompdf;
require_once 'dompdf/autoload.inc.php';

// Include your database connection file
require_once '../dbconnection.php';

// Get the order ID and address ID (you may modify how you retrieve these values)
$orderId = $_GET['orderId']; // Replace with your method of obtaining the order ID
$addressId = $_GET['addressId']; // Replace with your method of obtaining the address ID

try {
    // Fetch product info from the orders table based on the order ID
    $stmt = $conn->prepare("SELECT product_info FROM orders WHERE id = ?");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $productInfo = json_decode($row['product_info'], true);

        // Fetch address data based on the address ID
        $stmt = $conn->prepare("SELECT * FROM user_addresses WHERE id = ?");
        $stmt->bind_param("i", $addressId);
        $stmt->execute();
        $result = $stmt->get_result();
        $addressData = $result->fetch_assoc();

        // HTML template for the invoice
        $htmldata = '<!DOCTYPE html>
        <html>
        <head>
          <title>Invoice</title>
          <style>
            body {
              background-color: lightblue;
            }
            .container {
              width: 90%;
              max-width: 600px;
              margin: 0 auto;
            }
            table {
              width: 100%;
              border-collapse: collapse;
              margin-top: 20px;
            }
            th, td {
              border: 1px solid #000;
              padding: 8px;
              text-align: left;
            }
            th {
              background-color: #f2f2f2;
            }
            /* Add your CSS for the invoice here */
          </style>
        </head>
        <body>
          <div class="container">
            <!-- Logo goes here -->
            <img src="path_to_your_logo.jpg" alt="Your Logo">
            
            <h1>Invoice</h1>
            
            <!-- Product Table -->
            <table>
              <thead>
                <tr>
                  <th>Product Name</th>
                  <th>Price</th>
                </tr>
              </thead>
              <tbody>';

        // Loop through product info and add them to the table
        foreach ($productInfo as $product) {
            $htmldata .= '<tr>
              <td>' . $product['productName'] . '</td>
              <td>' . $product['productPrice'] . '</td>
            </tr>';
        }

        $htmldata .= '</tbody>
            </table>
            
            <!-- Total -->
            <h2>Total: $500.00</h2>
            
            <!-- Shipping Address -->
            <h2>Shipping Address</h2>
            <p>Name: ' . $addressData['name'] . '</p>
            <p>Mobile: ' . $addressData['mobile'] . '</p>
            <p>Email: ' . $addressData['email'] . '</p>
            <p>Address: ' . $addressData['address'] . '</p>
            <p>District: ' . $addressData['district'] . '</p>
            <p>Taluka: ' . $addressData['taluka'] . '</p>
            <p>Village: ' . $addressData['village'] . '</p>
            <p>Pincode: ' . $addressData['pincode'] . '</p>
            
          </div>
        </body>
        </html>';

        $dompdf = new Dompdf(); 
        $dompdf->loadHtml($htmldata);
        $customPaper = array(0, 0, 900, 1330);
        $dompdf->setPaper($customPaper);
        $dompdf->render();
        $dompdf->stream("invoice.pdf", array("Attachment" => false));
        exit(0);
    } else {
        echo "Order not found.";
    }
} catch (mysqli_sql_exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
