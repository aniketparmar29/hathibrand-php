<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>
</head>

<body>
    <nav class="flex z-50 flex-row justify-between items-center lg:sticky top-0 lg:px-5 px-2 bg-gray-200">
        <a href="./index.php"><img class="w-24" src="./assets/Logo/Favicon.ico" alt=""></a>
        <div class="relative">
            <input type="text" name="search" id="search" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pl-10"
                placeholder="Search...">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fa-solid fa-magnifying-glass" id="searchIcon"></i>
            </div>
        </div>

        <!-- Desktop navigation menu -->
        <ul class="desktop-menu text-red-500 hidden md:flex lg:flex md:text-lg flex-row gap-x-5 text-xl">
            <li class="hover:underline"><a href="index.php">Home</a></li>
            <li class="hover:underline"><a href="Products.php">Categories</a></li>
            <li class="hover:underline"><button type="button" data-drawer-target="drawer-right-example" data-drawer-show="drawer-right-example"
                    data-drawer-placement="right" aria-controls="drawer-right-example"><i class="fa fa-shopping-cart"></i></button></li>
            <?php if (isset($_SESSION['auth'])) { ?>
                <button id="dropdownHoverButton" data-dropdown-toggle="dropdownHover" data-dropdown-trigger="hover"
                    class="flex flex-row gap-x-1 items-center" type="button">
                    <p class="nav-item"> <?= $_SESSION['username']; ?></p>
                    <i class="fa-solid fa-caret-down ps-1"></i>
                </button>
                <!-- Dropdown menu -->
                <div id="dropdownHover" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownHoverButton">
                        <li>
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">My Order</a>
                        </li>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === "admin") { ?>
                            <li>
                                <a href="./Admin/admin.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Admin</a>
                            </li>
                        <?php } ?>
                        <li>
                            <a href="./logout.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Logout</a>
                        </li>
                    </ul>
                </div>
            <?php } else { ?>
                <li class="hover:underline"><a href="login.php">Login</a></li>
            <?php } ?>
        </ul>
    </nav>
    <div id="drawer-right-example" class="fixed top-0 right-0 z-50 h-screen p-4 overflow-y-auto transition-transform translate-x-full bg-white w-80 dark:bg-gray-800"
        tabindex="-1" aria-labelledby="drawer-right-label">
        <h5 id="drawer-right-label" class="inline-flex items-center mb-4 text-base font-semibold text-gray-500 dark:text-gray-400"><i
                class="fa fa-shopping-cart mr-2"></i>Cart</h5>
        <button type="button" data-drawer-hide="drawer-right-example" aria-controls="drawer-right-example"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 absolute top-2.5 right-2.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white">
            <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd"></path>
            </svg>
            <span class="sr-only">Close menu</span>
        </button>
        
        <div id="sidedrover">
            <!-- Cart items will be added dynamically here -->
        </div>
        <?php if (isset($_SESSION['auth'])) { ?>
        <a type="button"
            class="w-full bg-blue-500 text-white py-2 mt-4 rounded-md font-semibold hover:bg-blue-600 text-center"
            href="./checkout.php" >Checkout</a>
            <?php } else { ?>
                <a href="./login.php" type="button"
            class="w-full bg-blue-500 text-white py-2 mt-4 rounded-md font-semibold hover:bg-blue-600 text-center"
            onclick="ltcf()"> Checkout</a>
            <?php } ?>
    </div>

    <div id="search-results"></div>

    <section class="fixed mobile-menu block lg:hidden md:hidden bottom-0 inset-x-0 z-50 shadow-lg bg-white dark:bg-dark border-t-2 border-royal/20">
    <div id="tabs" class="flex justify-between items-center">
            <a href="index.php" class="w-full focus:text-royal hover:text-royal justify-center inline-block text-center pt-2 pb-1 hover:bg-gray-200">
                <div class="h-6 w-6 inline-block mb-1">
                    <i class="fa-solid text-red-500 fa-house"></i>
                </div>
                <span class="tab block text-xs font-extrabold text-yellow-600">Home</span>
            </a>
            <a href="Products.php" class="w-full focus:text-royal hover:text-royal justify-center inline-block text-center pt-2 pb-1 hover:bg-gray-200">
                <div class="h-6 w-6 inline-block mb-1">
                    <i class="fa-solid text-red-500 fa-dumpster"></i>
                </div>
                <span class="tab block text-xs font-extrabold text-yellow-600">Categories</span>
            </a>
           <!-- Add this code to display the cart item count in the navbar -->
          
           <button type="button" data-drawer-target="drawer-right-example" data-drawer-show="drawer-right-example" data-drawer-placement="right" aria-controls="drawer-right-example"  class="w-full focus:text-royal hover:text-royal justify-center inline-block text-center pt-2 pb-1  hover:bg-gray-200">
                        <div class="h-6 w-6 inline-block mb-1">
                            <i class="fa-solid text-red-500 fa-cart-shopping"></i>
                        </div>
                <?php
            // Get the cart items from localStorage
            $cartItems = isset($_SESSION['cartItems']) ? json_decode($_SESSION['cartItems'], true) : [];

            // Calculate the total quantity of items in the cart
            $totalQuantity = array_reduce($cartItems, function ($total, $item) {
                return $total + $item['quantity'];
            }, 0);
            ?>
            <span class="tab block text-xs font-extrabold text-yellow-600" >Cart <span id="cartItemCount" class="bg-red-600 rounded-full text-white px-2 py-1 text-xs"><?= $totalQuantity ?></span></span>
            </button>
           
        

            <?php if (isset($_SESSION['auth'])) { ?>
                <button  class=" gap-x-1 items-center w-full focus:text-royal hover:text-royal justify-center inline-block text-center pt-2 pb-1 hover:bg-gray-200" type="button"
                    onclick="openDropdown(event,'dropdown-id')">
                    <div class="h-6 w-6 inline-block mb-1">
                        <i class="fa-solid text-red-500 fa-user"></i>
                    </div>
                    <div>
                        <p class="nav-item tab block text-xs font-extrabold text-yellow-600"><?= $_SESSION['username']; ?></p>
                    </div>
                </button>
                <!-- Dropdown menu -->
                <div id="dropdown-id" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownHoverButton">
                        <li>
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">My Order</a>
                        </li>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === "admin") { ?>
                            <li>
                                <a href="./Admin/admin.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Admin</a>
                            </li>
                        <?php } ?>
                        <li>
                            <a href="./logout.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Logout</a>
                        </li>
                    </ul>
                </div>
            <?php } else { ?>
                <a href="login.php" class="w-full focus:text-royal hover:text-royal justify-center inline-block text-center pt-2 pb-1 hover:bg-gray-200">
                    <div class="h-6 w-6 inline-block mb-1">
                        <i class="fa-solid text-red-500 fa-user"></i>
                    </div>
                    <span class="tab block text-xs font-extrabold text-yellow-600">Login</span>
                </a>
            <?php } ?>
        </div>
    </section>
                  
                  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                  <script>
function ltcf(){
    alert("please login first")
}

function checkout(){
    console.log("op")
}


function openDropdown(event, dropdownID) {
        let element = event.target;
        while (element.nodeName !== "BUTTON") {
            element = element.parentNode;
        }
        var popper = Popper.createPopper(element, document.getElementById(dropdownID), {
            placement: 'top-end'
        });
        document.getElementById(dropdownID).classList.toggle("hidden");
        document.getElementById(dropdownID).classList.toggle("block");
    }
    document.addEventListener("DOMContentLoaded", function () {
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

        // Call the displayCartItems function on page load
        displayCartItems();

        // Function to handle adding items to the cart
        function addToCart(productId, productName, productPrice, productWeight, quantity) {
            const cartData = JSON.parse(localStorage.getItem('cart')) || [];
            const existingItemIndex = cartData.findIndex(item => item.productId === productId);

            if (existingItemIndex !== -1) {
                // If the item already exists in the cart, update its quantity
                cartData[existingItemIndex].quantity += quantity;
            } else {
                // Otherwise, add a new item to the cart
                cartData.push({
                    productId,
                    productName,
                    productPrice,
                    productWeight,
                    quantity,
                });
            }

            // Save the updated cart data to localStorage
            localStorage.setItem('cart', JSON.stringify(cartData));

            // Update the cart display
            displayCartItems();
        }

        // Function to update the cart items display when the cart is updated
        function updateCartDisplay() {
            displayCartItems();
        }

        // Add event listeners to update the cart display when the cart is updated
        document.querySelectorAll('[data-add-to-cart]').forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.dataset.productId;
                const productName = this.dataset.productName;
                const productPrice = this.dataset.productPrice;
                const productWeight = this.dataset.productWeight;
                const quantity = 1; // You can modify this to handle different quantities

                addToCart(productId, productName, productPrice, productWeight, quantity);
            });
        });

        // Call the displayCartItems function on page load
        displayCartItems();

        // Function to handle the checkout button click
        function checkout() {
            // Add your checkout logic here
            alert("Checkout button clicked!");
        }
    });

    function displayCartItemsCount() {
        const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
        const cartItemCount = cartItems.reduce((total, item) => total + item.quantity, 0);

        // Update the count in the navbar
        const cartCountElement = document.getElementById('cartItemCount');
        cartCountElement.textContent = cartItemCount;
    }
    displayCartItemsCount();
</script>
   <script src="https://unpkg.com/@popperjs/core@2.9.1/dist/umd/popper.min.js" charset="utf-8"></script>
</body>
</html>
