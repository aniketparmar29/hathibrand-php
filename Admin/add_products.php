<?php
    include "../dbconnection.php";
    if (!isset($_SESSION['auth']) || $_SESSION['role'] !== "admin") {
      echo "op";
      header('Location: ../index.php');
      exit(); // It's recommended to include an exit() statement after a header redirect
  }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/Logo/Favicon.ico" type="image/x-icon">
    <title>Add Products</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ml-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
   <span class="sr-only">Open sidebar</span>
   <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
      <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
   </svg>
</button>

<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
   <div class="h-full px-3 py-4 overflow-y-auto bg-gray-50 dark:bg-gray-800">
     <a href="../index.php" class="flex items-center pl-2.5 mb-5">
         <img src="../assets/Logo/Favicon.ico" class="h-6 mr-3 sm:h-7" alt="Flowbite Logo" />
         <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">Hathibrand</span>
      </a>
      <ul class="space-y-2 font-medium">
         <li>
            <a href="./admin.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
               <svg aria-hidden="true" class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path></svg>
               <span class="ml-3">Dashboard</span>
            </a>
         </li>
         <li>
            <a href="./product_listing.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
               <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
               <span class="flex-1 ml-3 whitespace-nowrap">Products</span>
            </a>
         </li>
        
         <li>
            <a href="./user.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
               <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
               <span class="flex-1 ml-3 whitespace-nowrap">Users</span>
            </a>
         </li>
         <li>
            <a href="" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
               <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path></svg>
               <span class="flex-1 ml-3 whitespace-nowrap">Orders</span>
            </a>
         </li>
         <li>
            <a href="./add_products.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
               <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path></svg>
               <span class="flex-1 ml-3 whitespace-nowrap">Add Product</span>
            </a>
         </li>
         
      </ul>
   </div>
</aside>
<div class="p-4 sm:ml-64">
    <div class="border-gray-200 rounded-lg dark:border-gray-700">

        <div class="container mx-auto mt-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Add Product Form -->
                <div class="bg-white rounded shadow flex flex-col justify-center items-center w-full m-auto">
                    <h2 class="text-lg font-bold mb-4">Add Product</h2>
                    <form method="POST" class="flex flex-col justify-center items-center p-2" enctype="multipart/form-data">
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
                        <button type="submit" name="add_product" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            Add Product
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

            <?php
            // include('../dbconnection.php');

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
    </div>
</div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>

</html>