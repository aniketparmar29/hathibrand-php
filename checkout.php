<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <?php require './components/Navbar.php' ?>

  <div class="container mx-auto">
    <h1 class="text-3xl font-bold mt-8 mb-4">Checkout</h1>
    <div class="flex flex-col lg:flex-row">
      <div class="w-full lg:w-2/3 mr-4">
        <!-- Cart Items -->
        <div id="cartItemsContainer"></div>
        <!-- Total Amount -->
        <div id="totalAmountContainer" class="flex justify-between p-2 border-b border-gray-200">
          <p class="font-semibold">Total:</p>
          <p class="font-semibold" id="totalAmount"></p>
        </div>
        <button id="placeOrderButton" class="mt-4 bg-blue-500 text-white font-semibold p-2 w-32 rounded">Place Order</button>
      </div>
      <div class="w-full lg:w-1/3 mt-4 lg:mt-0">
        <!-- Address Form -->
        <div class="bg-white p-4 shadow">
          <h2 class="text-lg font-bold mb-4">Shipping Address</h2>
          <div id="addressContainer"></div>
          <form id="addressForm" class="grid grid-cols-1 gap-4 md:grid-cols-2 hidden">
            <div>
              <label class="block">Name</label>
              <input type="text" id="nameInput" class="w-full p-2 border rounded" required>
            </div>
            <div>
              <label class="block">Mobile</label>
              <input type="tel" id="mobileInput" class="w-full p-2 border rounded" required>
            </div>
            <div>
              <label class="block">Alternative Mobile</label>
              <input type="tel" id="altMobileInput" class="w-full p-2 border rounded">
            </div>
            <div>
              <label class="block">District</label>
              <input type="text" id="districtInput" class="w-full p-2 border rounded" required>
            </div>
            <div>
              <label class="block">Taluka</label>
              <input type="text" id="talukaInput" class="w-full p-2 border rounded" required>
            </div>
            <div>
              <label class="block">Village</label>
              <input type="text" id="villageInput" class="w-full p-2 border rounded" required>
            </div>
            <div class="md:col-span-2">
              <label class="block">Address</label>
              <input type="text" id="addressInput" class="w-full p-2 border rounded" required>
            </div>
            <div>
              <label class="block">Pincode</label>
              <input type="text" id="pincodeInput" class="w-full p-2 border rounded" required>
            </div>
            <div class="md:col-span-2 flex justify-between">
              <button id="saveAddressButton" type="button" class="w-1/2 md:w-auto bg-green-500 text-white font-semibold p-2 rounded">Save Address</button>
              <button id="editAddressButton" class="w-1/2 md:w-auto bg-yellow-500 text-white font-semibold py-2 rounded hidden">Edit Address</button>
          <!-- Delete Address Button -->
          <button id="deleteAddressButton" class="w-1/2 md:w-auto bg-red-500 text-white font-semibold py-2 rounded hidden">Delete Address</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Footer -->
  <?php require './components/Footer.php' ?>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      function handlePlaceOrder() {
        // Check if address is available in localStorage
        const addressData = JSON.parse(localStorage.getItem('shippingAddress'));

        if (!addressData || Object.keys(addressData).length === 0) {
          // If address is not available, show an alert and do not place the order
          alert('Please provide a shipping address before placing the order.');
          return;
        }

        // Here, you can proceed with the order placement logic
        // For example, you can show a success message or redirect to a confirmation page.
        // Replace the following line with the actual order placement logic.

        alert('Order placed successfully!');
      }

      const placeOrderButton = document.getElementById('placeOrderButton');
      placeOrderButton.addEventListener('click', handlePlaceOrder);

    
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
      removeButton.classList.add('text-red-500', 'font-semibold', 'hover:text-red-700', 'cursor-pointer');
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

      // Function to handle the "Save Address" button click
      function handleSaveAddress(event) {
        event.preventDefault(); // Prevent the default form submission behavior
        const name = document.getElementById('nameInput').value;
        const mobile = document.getElementById('mobileInput').value;
        const altMobile = document.getElementById('altMobileInput').value;
        const district = document.getElementById('districtInput').value;
        const taluka = document.getElementById('talukaInput').value;
        const village = document.getElementById('villageInput').value;
        const address = document.getElementById('addressInput').value;
        const pincode = document.getElementById('pincodeInput').value;
        const addressData = { name, mobile, altMobile, district, taluka, village, address, pincode };

        // Save the address data to localStorage
        localStorage.setItem('shippingAddress', JSON.stringify(addressData));

        // Hide the address form and display the address details
        document.getElementById('addressForm').classList.add('hidden');
        document.getElementById('addressContainer').innerHTML = `
          <p><strong>Name:</strong> ${name}</p>
          <p><strong>Mobile:</strong> ${mobile}</p>
          <p><strong>Alternative Mobile:</strong> ${altMobile || 'N/A'}</p>
          <p><strong>District:</strong> ${district}</p>
          <p><strong>Taluka:</strong> ${taluka}</p>
          <p><strong>Village:</strong> ${village}</p>
          <p><strong>Address:</strong> ${address}</p>
          <p><strong>Pincode:</strong> ${pincode}</p>
        `;

        // Show the "Edit Address" and "Delete Address" buttons
        document.getElementById('editAddressButton').classList.remove('hidden');
        document.getElementById('deleteAddressButton').classList.remove('hidden');
        document.getElementById('saveAddressButton').classList.add('hidden');
      }

      // Function to handle the "Edit Address" button click
      function handleEditAddress() {
        // Show the address form for editing address and populate the form fields with existing address data
        const addressData = JSON.parse(localStorage.getItem('shippingAddress')) || {};
        document.getElementById('addressForm').classList.remove('hidden');
        document.getElementById('addressContainer').innerHTML = ''; // Clear the address details container
        document.getElementById('saveAddressButton').classList.remove('hidden');
        document.getElementById('editAddressButton').classList.add('hidden');
        document.getElementById('deleteAddressButton').classList.add('hidden');
        document.getElementById('nameInput').value = addressData.name || '';
        document.getElementById('mobileInput').value = addressData.mobile || '';
        document.getElementById('altMobileInput').value = addressData.altMobile || '';
        document.getElementById('districtInput').value = addressData.district || '';
        document.getElementById('talukaInput').value = addressData.taluka || '';
        document.getElementById('villageInput').value = addressData.village || '';
        document.getElementById('addressInput').value = addressData.address || '';
        document.getElementById('pincodeInput').value = addressData.pincode || '';
      }



      function displayAddressDetails() {
        const addressData = JSON.parse(localStorage.getItem('shippingAddress'));

        if (addressData && Object.keys(addressData).length !== 0) {
          // If address is available, show the address details along with edit and delete buttons
          document.getElementById('addressContainer').innerHTML = `
            <p><strong>Name:</strong> ${addressData.name}</p>
            <p><strong>Mobile:</strong> ${addressData.mobile}</p>
            <p><strong>Alternative Mobile:</strong> ${addressData.altMobile || 'N/A'}</p>
            <p><strong>District:</strong> ${addressData.district}</p>
            <p><strong>Taluka:</strong> ${addressData.taluka}</p>
            <p><strong>Village:</strong> ${addressData.village}</p>
            <p><strong>Address:</strong> ${addressData.address}</p>
            <p><strong>Pincode:</strong> ${addressData.pincode}</p>
          `;
          document.getElementById('addressForm').classList.add('hidden');
          document.getElementById('saveAddressButton').classList.add('hidden');
          document.getElementById('editAddressButton').classList.remove('hidden');
          document.getElementById('deleteAddressButton').classList.remove('hidden');
        } else {
          // If no address is available, show the address form to add a new address
          document.getElementById('addressContainer').innerHTML = '';
          document.getElementById('addressForm').classList.remove('hidden');
          document.getElementById('saveAddressButton').classList.remove('hidden');
          document.getElementById('editAddressButton').classList.add('hidden');
          document.getElementById('deleteAddressButton').classList.add('hidden');
          // Clear the input fields of the address form
          document.getElementById('nameInput').value = '';
          document.getElementById('mobileInput').value = '';
          document.getElementById('altMobileInput').value = '';
          document.getElementById('districtInput').value = '';
          document.getElementById('talukaInput').value = '';
          document.getElementById('villageInput').value = '';
          document.getElementById('addressInput').value = '';
          document.getElementById('pincodeInput').value = '';
        }
      }
      displayAddressDetails();
      // Function to handle the "Delete Address" button click
      function handleDeleteAddress() {
        // Delete the address data from localStorage and update the display
        localStorage.removeItem('shippingAddress');
        document.getElementById('addressContainer').innerHTML = ''; // Clear the container
        document.getElementById('addressForm').classList.remove('hidden'); // Show the address form
        document.getElementById('saveAddressButton').classList.remove('hidden');
        document.getElementById('editAddressButton').classList.add('hidden');
        document.getElementById('deleteAddressButton').classList.add('hidden');
      }

      // Add event listeners to the buttons
      const addAddressButton = document.getElementById('saveAddressButton');
      const editAddressButton = document.getElementById('editAddressButton');
      const deleteAddressButton = document.getElementById('deleteAddressButton');

      addAddressButton.addEventListener('click', handleSaveAddress);
      editAddressButton.addEventListener('click', handleEditAddress);
      deleteAddressButton.addEventListener('click', handleDeleteAddress);

      // AJAX to update address in localStorage on form submission
      const addressForm = document.getElementById('addressForm');
      addressForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent the default form submission behavior
        handleSaveAddress(event); // Call handleSaveAddress to save the address details



      });
    });
  </script>
</body>
</html>
