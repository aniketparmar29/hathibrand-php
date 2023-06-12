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

</head>

<body>
    <?php require './components/Navbar.php'?>

    <div class="container mx-auto grid gap-8 grid-cols-2 lg:grid-cols-4">
    <?php
// Fetch products from the database
$query = "SELECT * FROM categories limit 4";
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
    $product_category = $row['product_category'];
    $productPrice = $row['product_price'];

    // Check if the product is already in the wishlist
    ?>
            <div class="bg-white shadow-lg rounded-lg p-6 relative overflow-hidden">
        <?php if (!empty($productImage[0])) { ?>
            <a href="singleProduct.php?id=<?php echo $productID; ?>">
                <img src="./Admin/<?php echo $productImage[0]; ?>" alt="Product Image">
            </a>
        <?php } ?>
        <div class="mt-4">
            <h2 class="text-xl font-semibold"><?php echo $productName; ?></h2>
            <p class="text-lg mt-2">Weight:<?php echo $productWeight; ?></p>
            <div class="flex justify-between items-center mt-4">
                <span class="text-gray-600">
                    <i class="fas fa-rupee-sign"></i> <?php echo $productPrice; ?>
                </span>
                <div class="flex items-center space-x-2 absolute top-2 right-0">
                    <button class="text-red-500  transition-colors duration-300"
                        title="Add to Wishlist"
                        onclick="addToWishlist(<?php echo $productID; ?>, '<?php echo $productName; ?>', '<?php echo $productImage[0]; ?>', '<?php echo $productWeight; ?>', '<?php echo $productPrice; ?>')">
                        <i class="far fa-heart text-2xl hover:fa"></i>
                    </button>
                </div>
                <button class="text-blue-500 hover:text-blue-600 transition-colors duration-300" title="Add to Cart"
                    onclick="addToCart(<?php echo $productID; ?>, '<?php echo $productName; ?>', '<?php echo $productImage[0]; ?>', '<?php echo $productWeight; ?>', '<?php echo $productPrice; ?>')">
                    <i class="fas fa-shopping-cart text-2xl"></i>
                </button>

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
        <iframe  src="https://www.youtube.com/embed/IUcoX-9BL3U" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
    </div>
    <div class="flex flex-row justify-center items-center p-20 lg:block md:block hidden">
        <iframe width="750" height="500" class="m-auto" src="https://www.youtube.com/embed/IUcoX-9BL3U" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
    </div>
    <?php include './components/Footer.php'?>
</body>

</html>
<script>
     function addToCart(productID, productName, productImage, productWeight, productPrice) {
        // Check if localStorage is available
        if (typeof (Storage) !== "undefined") {
            // Retrieve existing cart items from localStorage
            var cartItems = localStorage.getItem("cart");
            var cart = [];

            if (cartItems !== null) {
                cart = JSON.parse(cartItems);
            }

            // Check if the product is already in the cart
            var existingProduct = cart.find(function (item) {
                return item.id === productID;
            });

            if (existingProduct) {
                // Product already in the cart, increase the weight
                if(existingProduct.weight>=1000){
                    existingProduct.weight += parseFloat(1000);
                    let opji=+existingProduct.price
                    existingProduct.price = opji+600;
                }else{
                    existingProduct.weight += parseFloat(productWeight);
                    let opji=+existingProduct.price
                    existingProduct.price = opji+150;
                }
                // Save the updated cart back to localStorage
                localStorage.setItem("cart", JSON.stringify(cart));

                // Provide feedback to the user
                alert("Product weight increased in the cart!");
            } else {
                // Add the product to the cart
                var newProduct = {
                    id: productID,
                    name: productName,
                    image: productImage,
                    weight: parseFloat(productWeight),
                    price: productPrice
                };

                cart.push(newProduct);

                // Save the updated cart back to localStorage
                localStorage.setItem("cart", JSON.stringify(cart));

                // Provide feedback to the user
                alert("Product added to the cart!");
            }
        } else {
            // localStorage is not available
            alert("Your browser does not support localStorage");
        }
    }

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
    var isProductInWishlist = wishlist.some(function(item) {
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

        // Display the session message as an alert
        $(document).ready(function() {
            // Make an AJAX request to fetch the session message
            $.ajax({
                url: 'fetch_message.php',
                type: 'GET',
                success: function(data) {
                    if (data !== '') {
                        // Display the session message as an alert
                        alert(data);
                    }
                }
            });
        });
</script>
<script src="https://unpkg.com/@popperjs/core@2.9.1/dist/umd/popper.min.js" charset="utf-8"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   