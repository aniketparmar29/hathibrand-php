<?php
include('dbconnection.php');

// Get sorting and filtering options from the query parameters
$sort = isset($_GET['sort']) ? $_GET['sort'] : ''; // Sorting option: price_htl, price_lth, weight_htl, weight_lth
$product_category = isset($_GET['product_category']) ? $_GET['product_category'] : ''; // Filtering option: product_category

// Construct the SQL query based on the sorting and filtering options
$sql = "SELECT * FROM categories";
if ($product_category !== '') {
    $sql .= " WHERE product_category = '$product_category'";
}

$order = '';
if ($sort === 'price_htl') {
    $order = 'ORDER BY product_price DESC';
} elseif ($sort === 'price_lth') {
    $order = 'ORDER BY product_price ASC';
} elseif ($sort === 'weight_htl') {
    $order = 'ORDER BY product_weight DESC';
} elseif ($sort === 'weight_lth') {
    $order = 'ORDER BY product_weight ASC';
}

if ($order !== '') {
    $sql .= " $order";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products Page</title>
    <link rel="shortcut icon" href="./assets/Logo/Favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

</head>
<body>
    <?php include './components/Navbar.php'?>

    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Products</h1>

        <div class="flex mb-4">
            <div class="mr-2">
                <label for="sort">Sort by:</label>
                <select id="sort" name="sort" onchange="location = this.value;">
                    <option value="">None</option>
                    <option value="?sort=price_htl">Price: High to Low</option>
                    <option value="?sort=price_lth">Price: Low to High</option>
                    <option value="?sort=weight_htl">Weight: High to Low</option>
                    <option value="?sort=weight_lth">Weight: Low to High</option>
                </select>
            </div>
            <div>
                <label for="product_category">Filter by Category:</label>
                <select id="product_category" name="product_category" onchange="location = this.value;">
                    <option value="">All</option>
                    <option value="?product_category=Agarbatti">Agarbatti</option>
                    <option value="?product_category=Cosmetic">Cosmetics</option>
                </select>
            </div>
        </div>

        <div class="container mx-auto grid gap-8 grid-cols-2 lg:grid-cols-4">
    <?php
    while ($row = mysqli_fetch_assoc($result)) {
        $productID = $row['id'];
        $productName = $row['product_name'];
        $productImage = explode(',', $row['product_images']);
        $productStock = $row['product_stock'];
        $productWeight = $row['display_weight'];
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
                    <button id="addToCartButton" class="text-blue-500 hover:text-blue-600 transition-colors duration-300" title="Add to Cart"
    onclick="addToCart(<?php echo $productID; ?>, '<?php echo $productName; ?>', '<?php echo $productImage[0]; ?>', '<?php echo $productPrice; ?>', '<?php echo $productWeight; ?>')">
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
</div>
<?php include './components/Footer.php'?>

</body>
</html>
<script>
     function displayCartItems() {
            const cartData = JSON.parse(localStorage.getItem('cartItems')) || [];
            const cartContainer = document.getElementById('sidedrover');
            const totalContainer = document.createElement('div');

            // Clear previous cart items
            cartContainer.innerHTML = '';

            if (cartData.length === 0) {
                // If cart is empty, show a message
                cartContainer.innerHTML = '<p class="text-center mt-4 text-gray-500">Cart is empty.</p>';
            } else {
                let totalAmount = 0;
                // Loop through each item in the cart and create HTML elements
                cartData.forEach(item => {
                    const cartItemDiv = document.createElement('div');
                    cartItemDiv.classList.add('flex', 'justify-between', 'items-center', 'p-2', 'border-b', 'border-gray-200');

                    const cartItemName = document.createElement('p');
                    cartItemName.textContent = item.productName;

                    const cartItemQuantity = document.createElement('p');
                    cartItemQuantity.textContent = `x${item.quantity}`;

                    const cartItemWeight = document.createElement('p');
                    cartItemWeight.textContent = `${item.productWeight}`;

                    const cartItemPrice = document.createElement('p');
                    const itemPrice = item.productPrice * item.quantity;
                    cartItemPrice.textContent = `₹${itemPrice}`;

                    cartItemDiv.appendChild(cartItemName);
                    cartItemDiv.appendChild(cartItemQuantity);
                    cartItemDiv.appendChild(cartItemWeight);
                    cartItemDiv.appendChild(cartItemPrice);

                    cartContainer.appendChild(cartItemDiv);

                    totalAmount += itemPrice;
                });

                // Display the total amount
                totalContainer.classList.add('flex', 'justify-between', 'items-center', 'p-2', 'border-b', 'border-gray-200');
                totalContainer.innerHTML = `
                    <p class="font-semibold flex-grow">Total:</p>
                    <p class="font-semibold">₹${totalAmount}</p>
                `;
                cartContainer.appendChild(totalContainer);
            }
        }
        function displayCartItemsCount() {
        const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
        const cartItemCount = cartItems.reduce((total, item) => total + item.quantity, 0);

        // Update the count in the navbar
        const cartCountElement = document.getElementById('cartItemCount');
        cartCountElement.textContent = cartItemCount;
    }

function addToCart(productId, productName, productImage, productPrice,wight) {
  const addToCartButton = document.getElementById('addToCartButton');
  addToCartButton.disabled = true; // Disable the button to prevent multiple clicks

  // Create the data object to be sent in the request
  const requestData = {
    productId: productId,
    productName: productName,
    productImage: productImage,
    productPrice: productPrice,
    productWeight: wight,
  };

  // Send the product data to the addToCart.php file using AJAX
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "addToCart.php", true);
  xhr.setRequestHeader("Content-Type", "application/json");

  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        // AJAX request was successful
        const response = JSON.parse(xhr.responseText);
        if (response.success) {
          // Update the cart display
          saveCartItemToLocalStorage(requestData);
          displayCartItems();
          displayCartItemsCount();

          alert("Item added to cart successfully!");

          // Open the side drawer after adding the item to the cart
          const drawer = document.getElementById("drawer-right-example");
          drawer.classList.remove("translate-x-full");
          drawer.classList.add("translate-x-0");
        } else {
          alert("Failed to add item to cart. Please try again.");
        }
      } else {
        alert("An error occurred while processing the request.");
      }
      
      addToCartButton.disabled = false; // Enable the button again, regardless of the response
    }
  };

  // Convert the requestData object to JSON and send it in the request body
  xhr.send(JSON.stringify(requestData));
}


function saveCartItemToLocalStorage(itemData) {
  // Retrieve existing cart items from localStorage
  const cartItems = JSON.parse(localStorage.getItem("cartItems")) || [];

  // Check if the product already exists in the cart
  const existingItemIndex = cartItems.findIndex(
    (item) =>
      item.productId === itemData.productId &&
      item.productWeight === itemData.productWeight
  );

  if (existingItemIndex !== -1) {
    // Product already exists in the cart, update the quantity
    cartItems[existingItemIndex].quantity++;
  } else {
    // Product doesn't exist in the cart, add it as a new item
    itemData.quantity = 1;
    cartItems.push(itemData);
  }

  // Save the updated cart data to the localStorage
  localStorage.setItem("cartItems", JSON.stringify(cartItems));

  // Update the cart items data in the session
  updateCartItemsInSession(cartItems);
}

function updateCartItemsInSession(cartItems) {
  // Make an AJAX request to update the cart items data in the session
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "updateCartItemsInSession.php", true);
  xhr.setRequestHeader("Content-Type", "application/json");

  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      console.log("Cart items data updated in session successfully!");
    }
  };

  // Convert the cart items data to JSON and send it in the request body
  xhr.send(JSON.stringify(cartItems));
}

// Call the displayCartItems function to check if the cart data is in localStorage
displayCartItems();

</script>

