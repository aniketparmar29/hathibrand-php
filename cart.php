<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="./assets/Logo/Favicon.ico" type="image/x-icon">
</head>
<body>
    <?php require './components/Navbar.php' ?>

    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Cart</h1>

        <div class="flex justify-between mb-4">
            <a href="products.php" class="text-blue-500 hover:text-blue-600 transition-colors duration-300">Back to Products</a>
            <button onclick="clearCart()" class="text-red-500 hover:text-red-600 transition-colors duration-300">Clear Cart</button>
        </div>

        <table class="w-full">
            <thead>
                <tr>
                    <th class="py-2">Product Name</th>
                    <th class="py-2">Weight</th>
                    <th class="py-2">Price</th>
                    <th class="py-2">Subtotal</th>
                    <th class="py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="cartItems">
                <!-- Cart items will be dynamically added here -->
            </tbody>
        </table>

        <div class="flex justify-between mt-4">
            <div>
                <p class="font-bold">Total Weight: <span id="totalWeight">0</span></p>
                <p class="font-bold">Total Price: <span id="totalPrice">0</span></p>
            </div>
            <div>
                <button onclick="checkoutOnline()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md mr-2">Checkout Online</button>
                <button onclick="checkoutCOD()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md">Cash on Delivery</button>
            </div>
        </div>
    </div>
    <?php require './components/Footer.php' ?>

    <script>
        function clearCart() {
            // Clear the cart by removing the "cart" item from localStorage
            localStorage.removeItem("cart");

            // Clear the cart items from the table
            document.getElementById("cartItems").innerHTML = "";

            // Update the total weight and total price
            updateCartSummary();
        }

        function checkoutOnline() {
            // Implement the online payment checkout logic here
            alert("Online payment checkout is not implemented yet!");
        }

        function checkoutCOD() {
            // Implement the cash on delivery checkout logic here
            alert("Cash on Delivery checkout is not implemented yet!");
        }

        function updateCartSummary() {
            // Retrieve the cart items from localStorage
            var cartItems = localStorage.getItem("cart");

            if (cartItems !== null) {
                // Parse the cart items from JSON format to an array
                var cart = JSON.parse(cartItems);

                // Calculate the total weight and total price
                var totalWeight = 0;
                var totalPrice = 0;

                // Loop through each item in the cart
                cart.forEach(function(item) {
                    var price = parseFloat(item.price);
                    var quantity = parseInt(item.quantity);

                    // Check if price and quantity are valid numbers
                    if (!isNaN(price) && !isNaN(quantity)) {
                        totalWeight += parseFloat(item.weight);
                        totalPrice += price * quantity;
                    }
                });

                // Update the total weight and total price in the summary
                document.getElementById("totalWeight").textContent = totalWeight.toFixed(2);
                document.getElementById("totalPrice").textContent = totalPrice.toFixed(2);
            } else {
                // Cart is empty, update the summary with default values
                document.getElementById("totalWeight").textContent = 0;
                document.getElementById("totalPrice").textContent = 0;
            }
        }

        // Retrieve cart items and update the cart summary when the page loads
        window.onload = function() {
            updateCartSummary();

            // Retrieve the cart items from localStorage
            var cartItems = localStorage.getItem("cart");

            if (cartItems !== null) {
                // Parse the cart items from JSON format to an array
                var cart = JSON.parse(cartItems);

                // Get the cart items container
                var cartItemsContainer = document.getElementById("cartItems");

                // Loop through each item in the cart
                cart.forEach(function(item) {
                    // Create a new row for each cart item
                    var row = document.createElement("tr");

                    // Create cells for product name, weight, price, and subtotal
                    var nameCell = document.createElement("td");
                    nameCell.textContent = item.name;
                    row.appendChild(nameCell);

                    var weightCell = document.createElement("td");
                    weightCell.textContent = item.weight;
                    row.appendChild(weightCell);

                    var priceCell = document.createElement("td");
                    priceCell.textContent = item.price;
                    row.appendChild(priceCell);

                    var subtotalCell = document.createElement("td");
                    subtotalCell.textContent = (parseFloat(item.price) * parseInt(item.quantity)).toFixed(2);
                    row.appendChild(subtotalCell);

                    // Create buttons for weight increase and decrease
                    var actionsCell = document.createElement("td");
                    var increaseButton = document.createElement("button");
                    increaseButton.textContent = "+";
                    increaseButton.classList.add("mr-1");
                    increaseButton.onclick = function() {
                        // Increase the weight of the item
                        item.weight += 0.1;
                        weightCell.textContent = item.weight;

                        // Update the cart summary
                        updateCartSummary();
                    };
                    actionsCell.appendChild(increaseButton);

                    var decreaseButton = document.createElement("button");
                    decreaseButton.textContent = "-";
                    decreaseButton.classList.add("mr-1");
                    decreaseButton.onclick = function() {
                        // Decrease the weight of the item, but not below 0
                        item.weight = Math.max(item.weight - 0.1, 0);
                        weightCell.textContent = item.weight;

                        // Update the cart summary
                        updateCartSummary();
                    };
                    actionsCell.appendChild(decreaseButton);

                    row.appendChild(actionsCell);

                    // Append the row to the cart items container
                    cartItemsContainer.appendChild(row);
                });
            }
        };
    </script>
</body>
</html>
