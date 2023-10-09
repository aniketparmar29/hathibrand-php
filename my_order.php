<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <!-- Include Tailwind CSS stylesheet -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<?php include './components/Navbar.php'?>

<div class="min-h-screen bg-gray-100 p-4">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-4">My Orders</h1>
        <?php
        // Include your database connection script (e.g., dbconnection.php)
        require_once 'dbconnection.php';

        // Check if the user is logged in (you should implement your own login mechanism)
        if (isset($_COOKIE['user_id'])) {
            $user_id = $_COOKIE['user_id'];

            // Retrieve all orders for the user
            $sql = "SELECT * FROM orders WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if there are orders
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="mb-4 p-4 border border-gray-300 rounded-lg">
                        <p class="text-lg font-semibold">Order ID: <?php echo $row['id']; ?></p>
                        <p>Payment: <?php echo $row['status']; ?></p>
                        <p>Created At: <?php echo $row['created_at']; ?></p>
                        
                        <!-- Display product cards -->
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php
                            $productInfo = json_decode($row['product_info'], true);
                            foreach ($productInfo as $product) {
                                ?>
                                <div class="bg-white rounded-lg overflow-hidden shadow-lg">
                                    <img src="./Admin/<?php echo $product['productImage']; ?>" alt="<?php echo $product['productName']; ?>" class="w-full h-48 object-cover">
                                    <div class="p-4">
                                        <p class="text-lg font-semibold"><?php echo $product['productName']; ?></p>
                                        <p class="text-gray-700">Price: $<?php echo $product['productPrice']; ?></p>
                                        <p class="text-gray-700">Weight: <?php echo $product['productWeight']; ?></p>
                                        <p class="text-gray-700">Quantity: <?php echo $product['quantity']; ?></p>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>

                       
                    </div>
                    <?php
                }
            } else {
                echo '<p class="text-gray-500">No orders found for this user.</p>';
            }

            // Close the database connection
            $stmt->close();
        } else {
            // User is not logged in; you can redirect them to the login page or handle as needed.
            echo '<p class="text-gray-500">You are not logged in.</p>';
        }

        // Close the database connection
        $conn->close();
        ?>
    </div>
</div>

<?php include './components/Footer.php'?>
</body>
</html>
