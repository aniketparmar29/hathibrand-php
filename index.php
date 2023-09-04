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
                    <span class="text-gray-600 text-lg font-bold">
                    ₹<?php echo $productPrice; ?>
                    </span>
                   
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

               


    <div class="flex flex-row justify-center items-center p-20 lg:hidden md:hidden block">
        <iframe  src="https://www.youtube.com/embed/IUcoX-9BL3U" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
    </div>
    <div class="flex flex-row justify-center items-center p-20 lg:block md:block hidden">
        <iframe width="750" height="500" class="m-auto" src="https://www.youtube.com/embed/IUcoX-9BL3U" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
    </div>
    <?php include './components/Footer.php'?>
</body>

</html>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

      // Create an image element for the product image
      const cartItemImage = document.createElement('img');
      cartItemImage.src = "./Admin/" + item.productImage;
      cartItemImage.alt = item.productName;
      cartItemImage.classList.add('w-16', 'h-16', 'object-cover', 'rounded');

      const cartItemDetails = document.createElement('div');
      cartItemDetails.classList.add('ml-4', 'flex-grow');

      // Create elements for product name, weight, and quantity
      const cartItemName = document.createElement('p');
      cartItemName.textContent = item.productName;
      const cartItemWeight = document.createElement('p');
      cartItemWeight.textContent = `${item.productWeight}`;
      const cartItemQuantity = document.createElement('p');
      cartItemQuantity.textContent = `x${item.quantity}`;
      const cartItemPrice = document.createElement('p');
      const itemPrice = item.productPrice * item.quantity;
      cartItemPrice.textContent = `₹${itemPrice}`;
      
      cartItemDetails.appendChild(cartItemName);
      cartItemDetails.appendChild(cartItemWeight);
      cartItemDetails.appendChild(cartItemQuantity);
      cartItemDetails.appendChild(cartItemPrice);


      cartItemDiv.appendChild(cartItemImage);
      cartItemDiv.appendChild(cartItemDetails);

      // Create a "Remove" button for the cart item
      const removeButton = document.createElement('button');
      removeButton.textContent = 'X';
      removeButton.classList.add('text-red-500', 'font-semibold', 'hover:text-red-700', 'cursor-pointer');
      removeButton.addEventListener('click', () => {
        removeCartItem(item.productId, item.productWeight);
      });

      cartItemDiv.appendChild(removeButton);
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

function removeCartItem(productId, productWeight) {
  const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

  // Find the index of the cart item to be removed
  const itemIndex = cartItems.findIndex(item => item.productId === productId && item.productWeight === productWeight);

  if (itemIndex !== -1) {
    // Remove the item from the cart
    cartItems.splice(itemIndex, 1);

    // Save the updated cart data to localStorage
    localStorage.setItem('cartItems', JSON.stringify(cartItems));

    // Update the cart display
    displayCartItemsCount(); // Update the cart count in the navbar
    displayCartItems(); // Update the cart items in the side drawer
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

          Swal.fire(
          'Cart Status',
          'Item added to cart successfully!',
          'success'
        )

          // Open the side drawer after adding the item to the cart
          const drawer = document.getElementById("drawer-right-example");
          drawer.classList.remove("translate-x-full");
          drawer.classList.add("translate-x-0");
        } else {
          Swal.fire(
          'Cart Status',
          'Failed to add item to cart. Please try again.',
          'error'
        )
        }
      } else {
        Swal.fire(
          'Cart Status',
          'An error occurred while processing the request.',
          'error'
        )
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

        // Display the session message as an alert
        $(document).ready(function() {
            // Make an AJAX request to fetch the session message
            $.ajax({
                url: 'fetch_message.php',
                type: 'GET',
                success: function(data) {
                    if (data !== '') {
                        // Display the session message as an alert
                        Swal.fire(
                          'Login Status',
                          data,
                          'success'
                        )
                    }
                }
            });
        });
</script>
<script src="https://unpkg.com/@popperjs/core@2.9.1/dist/umd/popper.min.js" charset="utf-8"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   