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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<h1 class="text-xl text-center p-10">Product Listing</h1>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th cope="col" class="px-6 py-3">ID</th>
                <th cope="col" class="px-6 py-3">Name</th>
                <th cope="col" class="px-6 py-3">Image</th>
                <th cope="col" class="px-6 py-3">Stock</th>
                <th cope="col" class="px-6 py-3">Weight</th>
                <th cope="col" class="px-6 py-3">Description</th>
                <th cope="col" class="px-6 py-3">Category</th>
                <th cope="col" class="px-6 py-3">Price</th>
                <th cope="col" class="px-6 py-3">Actions</th>
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
                $productImage = explode(',', $row['product_images']);
                $productStock = $row['product_stock'];
                $productWeight = $row['product_weight'];
                $productDesc = $row['product_desc'];
                $productCategory = $row['product_category'];
                $productPrice = $row['product_price'];
            ?>
                <tr c class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <td class="px-6 py-2 border-b text-left"><?php echo $productID; ?></td>
                    <td class="px-6 py-2 border-b text-left"><?php echo $productName; ?></td>
                    <td class="px-6 py-2 border-b">
                        <?php if (!empty($productImage[0])) { ?>
                            <img src="<?php echo $productImage[0]; ?>" alt="Product Image" class="h-16">
                        <?php } ?>
                    </td>

                    <td class="px-6 py-2 border-b text-left"><?php echo $productStock; ?></td>
                    <td class="px-6 py-2 border-b text-left"><?php echo $productWeight; ?></td>
                    <td class="px-6 py-2 border-b text-left"><?php echo $productDesc; ?></td>
                    <td class="px-6 py-2 border-b text-left"><?php echo $productCategory; ?></td>
                    <td class="px-6 py-2 border-b text-left"><?php echo $productPrice; ?></td>
                    <td cope="col" class="px-6 py-3 flex lg:flex-row md:flex-row flex-col gap-2">
                        <a href="edit_product.php?id=<?php echo $productID; ?>" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded transition-colors duration-300">Edit</a>
                        <a href="product_listing.php?delete_product=<?php echo $productID; ?>" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded transition-colors duration-300" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
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