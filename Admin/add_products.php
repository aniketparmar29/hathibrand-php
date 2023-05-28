<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/Logo/Favicon.ico" type="image/x-icon">
    <title>Add Products</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mx-auto mt-8">
        <h1 class="text-2xl mb-4 text-center">Product Management</h1>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Add Product Form -->
            <div class="bg-white p-4 rounded shadow flex flex-col justify-center items-center w-full m-auto ">
                <h2 class="text-lg font-bold mb-4">Add Product</h2>
                <form method="POST" class="flex flex-col justify-center items-center p-10 " enctype="multipart/form-data">
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
                        <label for="product_category" class="block mb-2">Categories:</label>
                        <input type="text" name="product_category" id="product_category" class="border rounded px-4 py-2 w-full" rows="4"></input>
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
                $product_Category = $_POST['product_category'];
                $product_price = $_POST['product_price'];

                // Format date as dd/mm/yyyy
                $current_date = date('d/m/Y');

                // Create a folder for the product
                $product_folder = 'assets/product/' . str_replace(' ', '', $product_name);
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

                $sql = "INSERT INTO categories (product_name, product_stock, product_weight, product_category, product_desc, product_price, product_date, product_images) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    die("Prepared statement error: " . $conn->error);
                }

                // Convert the image paths array to a comma-separated string
                $image_paths_string = implode(',', $image_paths);

                $stmt->bind_param("sissssss", $product_name, $product_stock, $product_weight, $product_Category, $product_desc, $product_price, $current_date, $image_paths_string);
                $stmt->execute();
                $stmt->close();

                // Close the database connection
                $conn->close();

                // Redirect the user to a different page
                header("Location: product_listing.php");
                exit();
            }
            ?>

            <!-- Establish a database connection -->

        </div>

    </div>

    </div>

</body>

</html>