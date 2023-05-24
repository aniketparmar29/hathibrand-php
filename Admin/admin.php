<?php
    include('../dbconnection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Products</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto mt-8">
        <h1 class="text-2xl mb-4">Product Management</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Add Product Form -->
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-lg font-bold mb-4">Add Product</h2>
                <form method="POST" action="add_product.php" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="product_name" class="block mb-2">Name:</label>
                        <input type="text" name="product_name" id="product_name" class="border rounded px-4 py-2 w-full" required>
                    </div>
                    <div class="mb-4">
                        <label for="product_image" class="block mb-2">Image:</label>
                        <input type="file" name="product_image" id="product_image" class="border rounded py-2 w-full" required>
                    </div>
                    <div class="mb-4">
                        <label for="product_stock" class="block mb-2">Stock:</label>
                        <input type="number" name="product_stock" id="product_stock" class="border rounded px-4 py-2 w-full" required>
                    </div>
                    <div class="mb-4">
                        <label for="product_weight" class="block mb-2">Weight:</label>
                        <input type="text" name="product_weight" id="product_weight" class="border rounded px-4 py-2 w-full" required>
                    </div>
                    <div class="mb-4">
                        <label for="product_desc" class="block mb-2">Description:</label>
                        <textarea name="product_desc" id="product_desc" class="border rounded px-4 py-2 w-full" rows="4"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="product_variants" class="block mb-2">Variants:</label>
                        <input type="text" name="product_variants" id="product_variants" class="border rounded px-4 py-2 w-full" required>
                    </div>
                    <button type="submit" name="add_product" class="bg-blue-500 text-white px-4 py-2 rounded">Add Product</button>
                </form>
            </div>

            <!-- Display Products -->
            <div class="col-span-2">
                <h2 class="text-lg font-bold mb-4">ProductListing</h2>
<table class="min-w-full bg-white border">
<thead>
<tr>
<th class="px-4 py-2 border-b">ID</th>
<th class="px-4 py-2 border-b">Name</th>
<th class="px-4 py-2 border-b">Image</th>
<th class="px-4 py-2 border-b">Stock</th>
<th class="px-4 py-2 border-b">Weight</th>
<th class="px-4 py-2 border-b">Description</th>
<th class="px-4 py-2 border-b">Variants</th>
</tr>
</thead>
<tbody>
<?php
                         // Fetch products from the database
                         $query = "SELECT * FROM products";
                         $result = mysqli_query($conn, $query);
                         while ($row = mysqli_fetch_assoc($result)) {
                             $productID = $row['id'];
                             $productName = $row['name'];
                             $productImage = $row['image'];
                             $productStock = $row['stock'];
                             $productWeight = $row['weight'];
                             $productDesc = $row['description'];
                             $productVariants = $row['variants'];
                     ?>
<tr>
<td class="px-4 py-2 border-b"><?php echo $productID; ?></td>
<td class="px-4 py-2 border-b"><?php echo $productName; ?></td>
<td class="px-4 py-2 border-b"><img src="<?php echo $productImage; ?>" alt="Product Image" class="h-16"></td>
<td class="px-4 py-2 border-b"><?php echo $productStock; ?></td>
<td class="px-4 py-2 border-b"><?php echo $productWeight; ?></td>
<td class="px-4 py-2 border-b"><?php echo $productDesc; ?></td>
<td class="px-4 py-2 border-b"><?php echo $productVariants; ?></td>
</tr>
<?php
                         }
                     ?>
</tbody>
</table>
</div>
</div>
</div>

</body>
</html>
```
