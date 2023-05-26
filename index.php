<?php
    include('dbconnection.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="./assets/Logo/Favicon.ico" type="image/x-icon">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hathibrand</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>

</head>

<body>
    <?php include './components/Navbar.php'?>

    <div class="container mx-auto grid gap-8 grid-cols-2 lg:grid-cols-4">
<?php
// Fetch products from the database
$query = "SELECT * FROM categories";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query execution failed: " . mysqli_error($conn));
}

// Retrieve existing wishlist items from localStorage
$wishlistItems = [];
if (isset($_SESSION['wishlist'])) {
    $wishlistItems = $_SESSION['wishlist'];
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

    // Check if the product is already in the wishlist
    $isInWishlist = false;
    foreach ($wishlistItems as $item) {
        if ($item['id'] == $productID) {
            $isInWishlist = true;
            break;
        }
    }
    ?>
    <div class="bg-white shadow-lg rounded-lg p-6 relative overflow-hidden">
        <div class=" overflow-hidden">
            <?php if (!empty($productImage[0])) { ?>
                <img src="./Admin/<?php echo $productImage[0]; ?>" alt="Product Image">
            <?php } ?>
        </div>
        <div class="mt-4">
            <h2 class="text-xl font-semibold"><?php echo $productName; ?></h2>
            <p class="text-gray-500 text-sm mt-2">Weight:<?php echo $productWeight; ?></p>
            <div class="flex justify-between items-center mt-4">
                <span class="text-gray-600">
                    <i class="fas fa-rupee-sign"></i> <?php echo $productPrice; ?>
                </span>
                <div class="flex items-center space-x-2">
                    <?php if ($isInWishlist) { ?>
                        <button class="text-red-500 hover:text-red-600 transition-colors duration-300"
                            title="Remove from Wishlist"
                            onclick="removeFromWishlist(<?php echo $productID; ?>)">
                            <i class="fas fa-heart"></i>
                        </button>
                    <?php } else { ?>
                        <button class="text-red-500 hover:text-red-600 transition-colors duration-300"
                            title="Add to Wishlist"
                            onclick="addToWishlist(<?php echo $productID; ?>)">
                            <i class="far fa-heart"></i>
                        </button>
                    <?php } ?>
                    <button class="text-blue-500 hover:text-blue-600 transition-colors duration-300"
                        title="Add to Cart">
                        <i class="fas fa-shopping-cart"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php
}

mysqli_free_result($result);
mysqli_close($conn);
?>
</div>


               


    <div class="flex flex-row justify-center items-center p-20 lg:hidden md:hidden block">
        <iframe  src="https://www.youtube.com/embed/RJ12OPLL4h8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
    </div>
    <div class="flex flex-row justify-center items-center p-20 lg:block md:block hidden">
        <iframe width="750" height="500" class="m-auto" src="https://www.youtube.com/embed/RJ12OPLL4h8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
    </div>
    <?php include './components/Footer.php'?>
</body>

</html>
<script>
    function addToWishlist(productID) {
        // Get the product details
        var productDetails = {
            id: productID,
            name: "<?php echo $productName; ?>",
            image: "<?php echo $productImage[0]; ?>",
            weight: "<?php echo $productWeight; ?>",
            price: "<?php echo $productPrice; ?>"
        };

        // Check if localStorage is available
        if (typeof (Storage) !== "undefined") {
            // Retrieve existing wishlist items from localStorage
            var wishlistItems = localStorage.getItem("wishlist");
            var wishlist = [];

            if (wishlistItems !== null) {
                wishlist = JSON.parse(wishlistItems);
            }

            // Add the product to the wishlist
            wishlist.push(productDetails);

            // Save the updated wishlist back to localStorage
            localStorage.setItem("wishlist", JSON.stringify(wishlist));

            // Provide feedback to the user
            alert("Product added to wishlist!");
        } else {
            // localStorage is not available
            alert("Your browser does not support localStorage")
        }
    }
</script>