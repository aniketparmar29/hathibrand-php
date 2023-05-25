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
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="product_name" class="block mb-2">Name:</label>
                        <input type="text" name="product_name" id="product_name" class="border rounded px-4 py-2 w-full" required>
                    </div>
                    <div class="mb-4">
                        <label for="product_images" class="block mb-2">Images:</label>
                        <input type="file" name="product_images[]" id="product_images" class="border rounded py-2 w-full" multiple required>
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
                        <label for="product_price" class="block mb-2">Price:</label>
                        <input type="text" name="product_price" id="product_price" class="border rounded px-4 py-2 w-full" required>
                    </div>
                    <button type="submit" name="add_product" class="bg-blue-500 text-white px-4 py-2 rounded">Add Product</button>
                </form>
            </div>
            <?php
    include('../dbconnection.php');

    // Check if the form is submitted
    if (isset($_POST['add_product'])) {
        // Retrieve form data
        $product_name = $_POST['product_name'];
        $product_stock = $_POST['product_stock'];
        $product_weight = $_POST['product_weight'];
        $product_desc = $_POST['product_desc'];
        $product_price = $_POST['product_price'];

        // Format date as dd/mm/yyyy
        $current_date = date('d/m/Y');

        // Create a folder for the product
        $product_folder = 'assets/product/' . $product_name;
        if (!is_dir($product_folder)) {
            mkdir($product_folder, 0777, true);
        }

        // Process image uploads
        $image_paths = array();

        foreach ($_FILES['product_images']['tmp_name'] as $key => $tmp_name) {
            $file_name = $_FILES['product_images']['name'][$key];
            $file_tmp = $_FILES['product_images']['tmp_name'][$key];
            $file_type = $_FILES['product_images']['type'][$key];
            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $file_path = $product_folder . '/' . $file_name;

            // Move the uploaded image to the product folder
            move_uploaded_file($file_tmp, $file_path);

            // Add the image path to the array
            $image_paths[] = $file_path;
        }

        $sql = "INSERT INTO categories (product_name, product_stock, product_weight, product_desc, product_price, product_date) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepared statement error: " . $conn->error);
        }
        
        $stmt->bind_param("sissss", $product_name, $product_stock, $product_weight, $product_desc, $product_price, $current_date);
        $stmt->execute();
        $stmt->close();
        
        echo $stmt;
        // Get the product ID of the newly inserted row
        $product_id = $conn->insert_id;

        // Insert image paths into the product_images table
        $image_sql = "INSERT INTO product_images (product_id, image_path) VALUES (?, ?)";
        $image_stmt = $conn->prepare($image_sql);

        foreach ($image_paths as $image_path) {
            $image_stmt->bind_param("is", $product_id, $image_path);
            $image_stmt->execute();
        }

        $image_stmt->close();
        $conn->close();

        echo "Product added successfully!";
    }
?>



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
<th class="px-4 py-2 border-b">Price</th>
</tr>
</thead>
<tbody>
<?php
    // Fetch products from the database
    $query = "SELECT * FROM categories";
    $result = conn_query($conn, $query);
    while ($row = conn_fetch_assoc($result)) {
        $productID = $row['product_id'];
        $productName = $row['product_name'];
        $productImage = $row['product_image'];
        $productStock = $row['product_stock'];
        $productWeight = $row['product_weight'];
        $productDesc = $row['product_desc'];
        $productPrice = $row['product_price'];
?>
<tr>
    <td class="px-4 py-2 border-b"><?php echo $productID; ?></td>
    <td class="px-4 py-2 border-b"><?php echo $productName; ?></td>
    <td class="px-4 py-2 border-b"><img src="<?php echo $productImage; ?>" alt="Product Image" class="h-16"></td>
    <td class="px-4 py-2 border-b"><?php echo $productStock; ?></td>
    <td class="px-4 py-2 border-b"><?php echo $productWeight; ?></td>
    <td class="px-4 py-2 border-b"><?php echo $productDesc; ?></td>
    <td class="px-4 py-2 border-b"><?php echo $productPrice; ?></td>
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
<script>
    document.querySelctor("form").addEventListner("sumbit",(e)=>{
        e.preventDefualt();
    })
</script>