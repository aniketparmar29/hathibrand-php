<?php
require_once './dbconnection.php'; // Include your database connection script

// Check if the user's ID (you may need to adjust how you retrieve this)
$user_id = $_COOKIE['user_id'];



?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css?family=Work+Sans:200,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css"/>
</head>
<body>
  <?php require './components/Navbar.php' ?>

  <div class="container ">
    <h1 class="text-3xl font-bold mt-8 mb-4 text-center">Checkout</h1>
    <div class="flex flex-col lg:flex-row">
      <div class="container lg:w-2/3 mx-auto">
        <div id="cartItemsContainer"></div>
        <!-- Total Amount -->
        <div id="totalAmountContainer" class="flex justify-between p-2 border-b border-gray-200">
          <p class="font-semibold">Total:</p>
          <p class="font-semibold" id="totalAmount"></p>
        </div>
        <button id="placeOrderButton" class="mt-4 bg-blue-500 text-white font-semibold p-2 w-32 rounded" type="submit" name="placeOrder">Place Order</button>

        <!-- Display User's Address or Add/Edit Link -->
        <?php if ($addressData !== null) : ?>
          <!-- Display User's Address -->
          <div class="mb-4">
            <h2 class="text-xl font-semibold mb-2">Your Address</h2>
            <p><strong>Name:</strong> <?php echo $addressData['name']; ?></p>
            <p><strong>Mobile:</strong> <?php echo $addressData['mobile']; ?></p>
            <p><strong>Email:</strong> <?php echo $addressData['email']; ?></p>
            <p><strong>Alternative Mobile:</strong> <?php echo $addressData['alt_mobile']; ?></p>
            <p><strong>District:</strong> <?php echo $addressData['district']; ?></p>
            <p><strong>Taluka:</strong> <?php echo $addressData['taluka']; ?></p>
            <p><strong>Village:</strong> <?php echo $addressData['village']; ?></p>
            <p><strong>Address:</strong> <?php echo $addressData['address']; ?></p>
            <p><strong>Pincode:</strong> <?php echo $addressData['pincode']; ?></p>
            <!-- Edit Address Link -->
            <a href="address.php" class="text-blue-500 hover:underline">
              <i class="fas fa-edit"></i> Edit Address
            </a>
          </div>
        <?php else : ?>
          <!-- User doesn't have an address, provide a link to add one -->
          <div class="mb-4">
            <p>You don't have an address on record. Please <a href="address.php" class="text-blue-500 hover:underline">add your address</a>.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <!-- Footer -->
  <?php require './components/Footer.php' ?>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>

document.addEventListener("DOMContentLoaded", function () {
        const placeOrderButton = document.getElementById('placeOrderButton');
        placeOrderButton.addEventListener('click', function (e) {
            e.preventDefault();

            // Prepare the order data to send to the server
            const client_txn_id = generateRandomClientId();
            const totalAmount = calculateTotalAmount();
            const cartData = JSON.parse(localStorage.getItem('cartItems')) || [];
            const user_id = <?php echo $user_id; ?>; // You may need to pass the user ID from your PHP code
            const address_id = <?php echo $addressData['id']; ?>; // Replace with the user's address ID

            if (cartData.length <= 0) {
                // Cart is empty, show an alert
                    Swal.fire({
                    icon: "error",
                    title: "Cart is empty",
                    text: "Please add items to your cart before placing an order.",
                    showConfirmButton: false,
                    timer: 2000,
                });
                return;
            }

            if (!user_id) {
               
                return;
            }

            // Prepare the order data to send to the server
            const orderData = {
                client_txn_id,
                amount: totalAmount,
                status:"Pending",
                product_info: cartData, 
                user_id,
                address_id,
            };
            
            fetch('create_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json', 
                },
                body: JSON.stringify(orderData), 
            })

.then((response) => {
       if (!response.ok) {
        throw new Error('Network response was not ok');
    }
    
    // Get the URL from the response body (assuming it's a string)
    return response.text();
})
.then((redirectUrl) => {
   
     window.open(redirectUrl, '_blank');
})
.catch((error) => {
  Swal.fire({
                    icon: "error",
                    title: "Something went wrong",
                    text: "Please check address and cart",
                    showConfirmButton: false,
                    timer: 2000,
  });
    console.error("Error:", error);
    // Handle other errors if needed
});

        });

        // Define functions for generating a random client transaction ID and calculating the total order amount
function generateRandomClientId() {
    // Implement your logic to generate a unique client transaction ID
    // You can use a combination of date, user ID, and random characters
    return "client_txn_" + Date.now() + "_" + Math.random().toString(36).substr(2, 10);
}

function calculateTotalAmount() {
    // Implement your logic to calculate the total order amount based on cart items
    // You can iterate through the cart data and sum the item prices
    const cartData = JSON.parse(localStorage.getItem('cartItems')) || [];
    let totalAmount = 0;
    cartData.forEach(item => {
        totalAmount += item.productPrice * item.quantity;
    });
    return totalAmount;
}
       
      function displayCartItems() {
  const cartData = JSON.parse(localStorage.getItem('cartItems')) || [];
  const cartItemsContainer = document.getElementById('cartItemsContainer');
  const totalAmountContainer = document.getElementById('totalAmount');
  
  // Clear previous cart items
  cartItemsContainer.innerHTML = '';
  totalAmountContainer.textContent = '';

  if (cartData.length === 0) {
    // If cart is empty, show a message
    cartItemsContainer.innerHTML = '<p class="text-center mt-4 text-gray-500">Cart is empty.</p>';
  } else {
    let totalAmount = 0;
    // Loop through each item in the cart and create HTML elements
    cartData.forEach(item => {
      // Create cart item container div
      const cartItemDiv = document.createElement('div');
      cartItemDiv.classList.add('flex', 'justify-between', 'items-center', 'p-2', 'border-b', 'border-gray-200');

      // Create an image element for the product image
      const cartItemImage = document.createElement('img');
      cartItemImage.src = "./Admin/" + item.productImage;
      cartItemImage.alt = item.productName;
      cartItemImage.classList.add('w-16', 'h-16', 'object-cover', 'rounded');

      // Create cart item details div
      const cartItemDetails = document.createElement('div');
      cartItemDetails.classList.add('ml-4', 'flex-grow');

      const cartItemName = document.createElement('p');
      cartItemName.textContent = item.productName;
      const cartItemWeight = document.createElement('p');
      cartItemWeight.textContent = `${item.productWeight}`;
      const cartItemQuantity = document.createElement('p');
      cartItemQuantity.textContent = `x${item.quantity}`;
      const cartItemPrice = document.createElement('p');
      const itemPrice = item.productPrice * item.quantity;
      cartItemPrice.textContent = `₹${itemPrice}`;

      // Append elements to cart item details div
      cartItemDetails.appendChild(cartItemName);
      cartItemDetails.appendChild(cartItemWeight);
      cartItemDetails.appendChild(cartItemQuantity);
      cartItemDetails.appendChild(cartItemPrice);

      // Append cart item image and details to container div
      cartItemDiv.appendChild(cartItemImage);
      cartItemDiv.appendChild(cartItemDetails);

      // Create a "Remove" button for the cart item
      const removeButton = document.createElement('button');
      removeButton.textContent = 'X';
      removeButton.classList.add('text-red-500', 'font-semibold', 'hover:text-red-700', 'cursor-pointer','border-2','rounded-md','p-3','px-4','shadow-md','shadow-red','hover:bg-gray-100');
      removeButton.addEventListener('click', () => {
        removeCartItem(item.productId, item.productWeight);
      });

      // Append remove button to cart item container div
      cartItemDiv.appendChild(removeButton);

      // Append cart item container div to cart items container
      cartItemsContainer.appendChild(cartItemDiv);

      totalAmount += itemPrice;
    });

    // Display the total amount
    totalAmountContainer.textContent = `₹${totalAmount}`;
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

        // Call the displayCartItems function on page load
        displayCartItems();

      });
  </script>
</body>
</html>