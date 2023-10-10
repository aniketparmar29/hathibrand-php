<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Ecommerce Invoice</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

.invoice {
    max-width: 210mm;
    margin: 20px auto;
    border: 1px solid #000;
    padding: 20px;
}

.header {
    display: flex;
    align-items: center;
}

.header img {
    max-width: 150px;
}

.address {
    margin-left: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    border: 1px solid #000;
    text-align: left;
    padding: 8px;
}

th {
    background-color: #f2f2f2;
}

.total {
    margin-top: 20px;
}

.total h3 {
    text-align: right;
    margin-top: 10px;
}

    </style>
</head>
<body>
    <div class="invoice">
        <div class="header">
            <img src="your-logo.png" alt="Your Logo">
            <div class="address">
                <h2>Your Company Name</h2>
                <p>123 Main Street</p>
                <p>City, State, ZIP</p>
                <p>Email: contact@yourcompany.com</p>
            </div>
        </div>
        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Product 1</td>
                        <td>Description of Product 1</td>
                        <td>2</td>
                        <td>$10.00</td>
                        <td>$20.00</td>
                    </tr>
                    <tr>
                        <td>Product 2</td>
                        <td>Description of Product 2</td>
                        <td>3</td>
                        <td>$15.00</td>
                        <td>$45.00</td>
                    </tr>
                    <!-- Add more rows for additional products -->
                </tbody>
            </table>
        </div>
        <div class="total">
            <p>Subtotal: $65.00</p>
            <p>Tax (10%): $6.50</p>
            <h3>Total Amount: $71.50</h3>
        </div>
    </div>
</body>
</html>
