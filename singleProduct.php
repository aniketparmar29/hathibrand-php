<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="./assets/Logo/Favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    include('dbconnection.php');

    // Initialize the $productName variable
    $productName = '';

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
            $product_category = $row['product_category'];
            $productPrice = $row['product_price'];
            $weights = explode(',', $row['weights']);
            // The rest of your code to display the product details goes here
            ?>
            <title><?php echo $productName; ?></title>
            <?php
        }
    }
    ?>
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
        $product_category = $row['product_category'];
        $productPrice = $row['product_price'];
        $weights = explode(',', $row['weights']);
        // The rest of your code to display the product details goes here
        ?>

        <!-- HTML and PHP code for displaying the single product -->
        <div class="flex flex-col lg:flex-row md:flex-row justify-around items-center bg-white shadow-lg rounded-lg p-6 relative overflow-hidden">
            <div class="carousel relative">
                <?php foreach ($productImage as $key => $image) { ?>
                    <img src="./Admin/<?php echo $image; ?>" alt="Product Image"
                         class="<?php echo $key === 0 ? 'block' : 'hidden'; ?> transition duration-500 lg:w-96 md:w-96">
                <?php } ?>
                <button class="carousel-btn carousel-prev" onclick="carouselPrevious()">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="carousel-btn carousel-next" onclick="carouselNext()">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <div class="mt-4 flex flex-col  justify-around items-center">
                <h2 class="text-2xl font-bold mb-10"><?php echo $productName; ?></h2>
                <div class="flex items-center mt-2">
                </div>
                <span class="text-gray-600">
            <p id="priceop">₹ <?php echo $productPrice; ?></p>
        </span>
                <div class="mt-4 flex flex-col justify-around items-center gap-y-5">
                    <h2 class="text-xl font-bold mb-2">Select Weight:</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <?php
                        // Assuming the weight prices are stored in a column named 'price_list' in the same table
                        // Fetch the price list from the database
                        $priceList = json_decode($row['price_list'], true);
                        foreach ($weights as $index => $weight) { ?>
                            <label class="flex items-center  border-2 p-2 rounded-lg ">
                                <input type="radio" name="selectedWeight" value="<?php echo $weight; ?>" <?php echo ($index === 0) ? 'checked' : ''; ?>>
                                <span class="ml-2"><?php echo $weight; ?></span>
                            </label>
                        <?php } ?>
                    </div>
                    <div class="flex flex-col justify-between items-center mt-4 gap-y-5">

                    <button onclick="addToCart(<?php echo $productID; ?>, '<?php echo $productName; ?>', '<?php echo $productImage[0]; ?>', '<?php echo $productPrice; ?>')" 
  class="text-white bg-yellow-500 rounded-lg p-4 hover:text-blue-600 transition-colors duration-300 flex flex-row justify-between w-40" 
  title="Add to Cart" id="addToCartButton">
  <span> Add To Cart</span>
  <i class="fas fa-shopping-cart text-2xl"></i>
</button>



                    </div>
                </div>
            </div>
        </div>

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

function addToCart(productId, productName, productImage, productPrice) {
  const addToCartButton = document.getElementById('addToCartButton');
  addToCartButton.disabled = true; // Disable the button to prevent multiple clicks

  const selectedWeight = getSelectedWeight();
  if (!selectedWeight) {
    alert("Please select a weight option.");
    addToCartButton.disabled = false; // Enable the button again
    return;
  }
  const updatedPrice = weightPrices[selectedWeight];

  // Create the data object to be sent in the request
  const requestData = {
    productId: productId,
    productName: productName,
    productImage: productImage,
    productPrice: updatedPrice,
    productWeight: selectedWeight,
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


    function getSelectedWeight() {
        const selectedWeight = document.querySelector('input[name="selectedWeight"]:checked');
        return selectedWeight ? selectedWeight.value : null;
    }

            const priceElement = document.getElementById('priceop');
            const initialPrice = <?php echo $productPrice; ?>;

            // Parse the price list JSON and store it in an associative array
            const weightPrices = <?php echo json_encode($priceList); ?>;

            document.querySelectorAll('input[name="selectedWeight"]').forEach(function (radio) {
                radio.addEventListener('change', function () {
                    const selectedWeight = getSelectedWeight();
                    if (selectedWeight) {
                        const updatedPrice = weightPrices[selectedWeight];
                        priceElement.textContent = '₹ ' + updatedPrice;
                    } else {
                        priceElement.textContent = '₹ ' + initialPrice;
                    }
                });
            });

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
