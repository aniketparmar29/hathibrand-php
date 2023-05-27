<?php
include('dbconnection.php');?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>    <link rel="shortcut icon" href="./assets/Logo/Favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.7/dist/tailwind.min.css" rel="stylesheet">
<style>
    .carousel-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: transparent;
    border: none;
    color: #000;
    font-size: 24px;
    cursor: pointer;
}

.carousel-prev {
    left: 10px;
}

.carousel-next {
    right: 10px;
}

</style>
</head>
<body>
<?php include './components/Navbar.php'?>
    
<?php

// Assuming the product ID is passed as a URL parameter named 'id'
if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    
    // Fetch the single product based on the ID
    $query = "SELECT * FROM categories WHERE id = $productId";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $productID = $row['id'];
        $productName = $row['product_name'];
        $productImage = explode(',', $row['product_images']);
        $productStock = $row['product_stock'];
        $productWeight = $row['product_weight'];
        $productDesc = $row['product_desc'];
        $product_category = $row['product_category'];
        $productPrice = $row['product_price'];
        
        // The rest of your code to display the product details goes here
?>

<!-- HTML and PHP code for displaying the single product -->
<div class="container mx-auto grid gap-8 grid-cols-2 lg:grid-cols-4">
    <div class="bg-white shadow-lg rounded-lg p-6 relative overflow-hidden">
        <div class="carousel relative">
            <?php foreach ($productImage as $key => $image) { ?>
                <img src="./Admin/<?php echo $image; ?>" alt="Product Image" class="transition duration-500">
            <?php } ?>
            <button class="carousel-btn carousel-prev" onclick="carouselPrevious()">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="carousel-btn carousel-next" onclick="carouselNext()">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
        <div class="mt-4">
            <h2 class="text-xl font-semibold"><?php echo $productName; ?></h2>
            <p class="text-lg mt-2">Weight: <?php echo $productWeight; ?></p>
            <div class="flex justify-between items-center mt-4">
                <span class="text-gray-600">
                    <i class="fas fa-rupee-sign"></i> <?php echo $productPrice; ?>
                </span>
                <div class="flex items-center space-x-2 absolute top-2 right-0">
                    <button class="text-red-500 transition-colors duration-300"
                        title="Add to Wishlist"
                        onclick="addToWishlist(<?php echo $productID; ?>, '<?php echo $productName; ?>', '<?php echo $productImage[0]; ?>', '<?php echo $productWeight; ?>', '<?php echo $productPrice; ?>')">
                        <i class="far fa-heart text-2xl hover:fa"></i>
                    </button>
                </div>
                <button class="text-blue-500 hover:text-blue-600 transition-colors duration-300"
                    title="Add to Cart">
                    <i class="fas fa-shopping-cart text-2xl"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    var currentIndex = 0;
    var carouselImages = document.querySelectorAll('.carousel img');
    var totalImages = carouselImages.length;
    
    function showImage(index) {
        if (index >= 0 && index < totalImages) {
            carouselImages.forEach(function (image) {
                image.style.display = 'none';
            });
            carouselImages[index].style.display = 'block';
        }
    }

    function carouselPrevious() {
        currentIndex--;
        if (currentIndex < 0) {
            currentIndex = totalImages - 1;
        }
        showImage(currentIndex);
    }
    
    function carouselNext() {
        currentIndex++;
        if (currentIndex >= totalImages) {
            currentIndex = 0;
        }
        showImage(currentIndex);
    }
    showImage(currentIndex);
    function addToWishlist(productID, productName, productImage, productWeight, productPrice) {
        // Get the product details
        var productDetails = {
            id: productID,
            name: productName,
            image: productImage,
            weight: productWeight,
            price: productPrice
        };

        // Check if localStorage is available
        if (typeof (Storage) !== "undefined") {
            // Retrieve existing wishlist items from localStorage
            var wishlistItems = localStorage.getItem("wishlist");
            var wishlist = [];

            if (wishlistItems !== null) {
                wishlist = JSON.parse(wishlistItems);
            }

            // Check if the product is already in the wishlist
            var isProductInWishlist = wishlist.some(function (item) {
                return item.id === productID;
            });

            if (isProductInWishlist) {
                // Product already in wishlist, display message to the user
                alert("Product is already in the wishlist!");
            } else {
                // Add the product to the wishlist
                wishlist.push(productDetails);

                // Save the updated wishlist back to localStorage
                localStorage.setItem("wishlist", JSON.stringify(wishlist));

                // Provide feedback to the user
                alert("Product added to wishlist!");
            }
        } else {
            // localStorage is not available
            alert("Your browser does not support localStorage")
        }
    }
</script>

<?php
} else {
    echo "Product not found.";
}
} else {
echo "Invalid product ID.";
}
?>
<?php include './components/Footer.php'?>

</body>
</html>