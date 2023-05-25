<?php
include('../dbconnection.php');

// Check if the delete button is clicked
if (isset($_GET['delete_product'])) {
    $productID = $_GET['delete_product'];

    // Delete the product from the database
    $deleteQuery = "DELETE FROM categories WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    if (!$stmt) {
        die("Prepared statement error: " . $conn->error);
    }
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $stmt->close();

    // Redirect to the product listing page after deletion
    header("Location: product_listing.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/Logo/Favicon.ico" type="image/x-icon">
    <title>Product Listing</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto mt-8">
        <h1 class="text-2xl mb-4">Product Listing</h1>
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="px-4 py-2 border-b">ID</th>
                    <th class="px-4 py-2 border-b">Name</th>
                    <th class="px-4 py-2 border-b">Image</th>
                    <th class="px-4 py-2 border-b">Stock</th>
                    <th class="px-4 py-2 border-b">Weight</th>
                    <th class="px-4 py-2 border-b">Description</th>
                    <th class="px-4 py-2 border-b">Price</th>
                    <th class="px-4 py-2 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch products from the database
                $query = "SELECT * FROM categories";
                $result = mysqli_query($conn, $query);

                if (!$result) {
                    die("Query execution failed: " . mysqli_error($conn));
                }

                while ($row = mysqli_fetch_assoc($result)) {
                    $productID = $row['id'];
                    $productName = $row['product_name'];
                    $productImage = $row['product_images'];
                    $productStock = $row['product_stock'];
                    $productWeight = $row['product_weight'];
                    $productDesc = $row['product_desc'];
                    $productPrice = $row['product_price'];
                ?>
                    <tr>
                        <td class="px-4 py-2 border-b"><?php echo $productID; ?></td>
                        <td class="px-4 py-2 border-b"><?php echo $productName; ?></td>
                        <td class="px-4 py-2 border-b">
                <?php foreach ($productImage as $image) { ?>
                    <img src="<?php echo $image; ?>" alt="Product Image" class="h-16">
                <?php } ?>
            </td>
                        <td class="px-4 py-2 border-b"><?php echo $productStock; ?></td>
                        <td class="px-4 py-2 border-b"><?php echo $productWeight; ?></td>
                        <td class="px-4 py-2 border-b"><?php echo $productDesc; ?></td>
                        <td class="px-4 py-2 border-b"><?php echo $productPrice; ?></td>
                        <td class="px-4 py-2 border-b">
                            <a href="edit_product.php?id=<?php echo $productID; ?>" class="bg-blue-500 text-white px-4 py-2 rounded">Edit</a>
                            <a href="product_listing.php?delete_product=<?php echo $productID; ?>" class="bg-red-500 text-white px-4 py-2 rounded" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                        </td>
                    </tr>
                <?php
                }
                mysqli_free_result($result);
                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>