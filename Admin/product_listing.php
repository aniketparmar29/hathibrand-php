<?php
    include "../dbconnection.php";
    if (!isset($_COOKIE['auth']) || $_COOKIE['role'] !== "admin") {
      echo "op";
      header('Location: ../index.php');
      exit(); // It's recommended to include an exit() statement after a header redirect
  }


// Check if the delete button is clicked
if (isset($_GET['delete_product'])) {
    $productID = $_GET['delete_product'];

    // Delete the product from the database
    $deleteQuery = "DELETE FROM categories WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    
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
            <a href="./Order.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
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
   <div class="p-4  border-gray-200  rounded-lg dark:border-gray-700">
 



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
                $productWeight = $row['display_weight'];
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
         
   </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>

</body>
</html>