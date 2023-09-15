<?php
    include "../dbconnection.php";
    if (!isset($_COOKIE['auth']) || $_COOKIE['role'] !== "admin") {
      echo "op";
      header('Location: ../index.php');
      exit(); // It's recommended to include an exit() statement after a header redirect
  }


// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the product ID from the form
    $productID = $_POST['product_id'];

    // Retrieve the updated product details from the form
    $productName = $_POST['product_name'];
    $productStock = $_POST['product_stock'];
    $productWeight = $_POST['product_weight'];
    $productDesc = $_POST['product_desc'];
    $productCategory = $_POST['product_category'];
    $productPrice = $_POST['product_price'];

    // Update the product in the database
    $updateQuery = "UPDATE categories SET product_name = ?, product_stock = ?, product_weight = ?, product_desc = ?, product_category = ?, product_price = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    if (!$stmt) {
        die("Prepared statement error: " . $conn->error);
    }
    $stmt->bind_param("sisssii", $productName, $productStock, $productWeight, $productDesc, $productCategory, $productPrice, $productID);
    $stmt->execute();
    $stmt->close();

    // Redirect to the product listing page after updating
    header("Location: product_listing.php");
    exit();
}

// Retrieve the product ID from the query parameter
if (isset($_GET['id'])) {
    $productID = $_GET['id'];

    // Fetch the product from the database
    $selectQuery = "SELECT * FROM categories WHERE id = ?";
    $stmt = $conn->prepare($selectQuery);
    if (!$stmt) {
        die("Prepared statement error: " . $conn->error);
    }
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    // Check if the product exists
    if (!$product) {
        die("Product not found.");
    }
} else {
    // Redirect to the product listing page if no product ID is provided
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
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flowbite/css/flowbite.css" rel="stylesheet">
</head>
<body>
    <div class="mt-8 shadow-md shadow-red p-4 lg:w-60 md:w-60 lg:mx-auto md:mx-auto mx-2">
        <h1 class="text-2xl mb-4 text-center">Edit Product</h1>
        <form class="flex flex-col justify-center items-center" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="product_id" value="<?php echo $productID; ?>">
    <div class="mb-4">
        <label for="product_name" class="block">Product Name</label>
        <input type="text" name="product_name" id="product_name" value="<?php echo $product['product_name']; ?>" required>
    </div>
    <div class="mb-4">
        <label for="product_stock" class="block">Stock</label>
        <input type="number" name="product_stock" id="product_stock" value="<?php echo $product['product_stock']; ?>" required>
    </div>
    <div class="mb-4">
        <label for="product_weight" class="block">Weight</label>
        <input type="text" name="product_weight" id="product_weight" value="<?php echo $product['product_weight']; ?>" required>
    </div>
    <div class="mb-4">
        <label for="product_desc" class="block">Description</label>
        <textarea name="product_desc" id="product_desc" rows="4" required><?php echo $product['product_desc']; ?></textarea>
    </div>
    <div class="mb-4">
        <label for="product_category" class="block">Category</label>
        <select name="product_category" id="product_category" required>
                <option value="">Select a category</option>
                <option value="Agarbatti" <?php echo ($product['product_category'] === 'Agarbatti') ? 'selected' : ''; ?>>Agarbatti</option>
                <option value="Cosmetics" <?php echo ($product['product_category'] === 'Cosmetics') ? 'selected' : ''; ?>>Cosmetics</option>
        </select>
    </div>
    <div class="mb-4">
        <label for="product_price" class="block">Price</label>
        <input type="number" name="product_price" id="product_price" value="<?php echo $product['product_price']; ?>" step="0.01" required>
    </div>
    
    <div class="mt-4">
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
            Update Product
        </button>
    </div>
</form>
</div>
</body>
</html>
