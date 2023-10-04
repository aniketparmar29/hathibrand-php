<?php
require_once './dbconnection.php'; // Include your database connection script

// Check if the user's ID (you may need to adjust how you retrieve this)
$user_id = $_COOKIE['user_id'];

// Check if the user has an existing address
$sql = "SELECT * FROM `user_addresses` WHERE `user_id` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User has an existing address, fetch it
    $addressData = $result->fetch_assoc();
} else {
    // User does not have an address, set $addressData to null
    $addressData = null;
}

$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle the form submission
    $cartItemsCookie = $_COOKIE['cart_items'];

    // Check if the cart_items cookie is set
    if (!isset($cartItemsCookie)) {
        echo json_encode(array('success' => false, 'message' => 'Cart is empty or missing cart_items cookie.'));
        exit;
    }

    // Retrieve cart items from the cookie
    $cartItems = json_decode($cartItemsCookie, true);

    // Check if the cart is empty
    if (empty($cartItems)) {
        echo json_encode(array('success' => false, 'message' => 'Cart is empty.'));
        exit;
    }

    // Prepare the order data
    $client_txn_id = generateRandomClientId();
    $totalAmount = calculateTotalAmount($cartItems);
    $status = 'Pending'; // You can set the initial status here
    $address_id = $addressData['id']; // Replace with the user's address ID

    // Insert the order into the database
    $sql = "INSERT INTO `orders` (`client_txn_id`, `amount`, `product_info`, `status`, `address_id`, `created_at`, `user_id`)
            VALUES (?, ?, ?, ?, ?, NOW(), ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdssii", $client_txn_id, $totalAmount, json_encode($cartItems), $status, $address_id, $user_id);

    if ($stmt->execute()) {
        // Order placed successfully, clear the cart cookie
        setcookie('cart_items', '', time() - 3600, '/'); // Clear the cart_items cookie
        echo json_encode(array('success' => true, 'message' => 'Order placed successfully.'));
        exit;
    } else {
        echo json_encode(array('success' => false, 'message' => 'Error placing the order.'));
        exit;
    }
}

// Function to generate a random client transaction ID
function generateRandomClientId() {
    return "client_txn_" . uniqid();
}

// Function to calculate the total order amount
function calculateTotalAmount($cart) {
    $totalAmount = 0;
    foreach ($cart as $item) {
        $totalAmount += $item['productPrice'] * $item['quantity'];
    }
    return $totalAmount;
}
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
        <!-- Cart Items -->
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
                console.log(cartData.length);
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
                // User is not logged in, handle accordingly
                // You may redirect the user to the login page or display an error message
                return;
            }

            // Prepare the order data to send to the server
            const orderData = {
                client_txn_id,
                amount: totalAmount,
                product_info: cartData, // Send cartData directly, no need to stringify it here
                user_id,
                address_id,
            };

            // Send an AJAX request to create the order
            fetch('create_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json', // Set the content type to JSON
                },
                body: JSON.stringify(orderData), // Convert orderData to JSON string
            })
            .then((response) => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then((data) => {
                console.log(data);
                if (data.success) {
                    // Order placed successfully, show a success message and redirect
                    Swal.fire({
                        icon: "success",
                        title: "Order Placed Successfully",
                        text: "Your order has been placed successfully.",
                        showConfirmButton: false,
                        timer: 2000,
                    }).then(function () {
                        // Redirect to the order history page or any other page
                        window.location.href = "order_history.php";
                    });
                } else {
                    // Error occurred while placing the order, show an error message
                    Swal.fire({
                        icon: "error",
                        title: "Order Placement Error",
                        text:
                        data.message ||
                        "An error occurred while placing your order. Please try again later.",
                        showConfirmButton: false,
                        timer: 2000,
                    });
                }
            })
            .catch((error) => {
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

      // Create elements for product name, weight, quantity, and price
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