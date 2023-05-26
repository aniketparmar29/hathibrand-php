<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Wishlist Page HTML -->
<!-- Wishlist Page HTML -->
<?php include './components/Navbar.php'?>

<!-- Wishlist Page HTML -->
<h1 class="text-2xl text-center">Wishlist</h1>
<div class="container mx-auto">
    <div id="wishlist-container" class="grid gap-8 grid-cols-2 lg:grid-cols-4"></div>
  </div>
  <?php include './components/Footer.php'?>
  

<!-- JavaScript code -->
<script>
// Wishlist Page JavaScript
document.addEventListener("DOMContentLoaded", function() {
  var wishlistContainer = document.getElementById("wishlist-container");

  // Retrieve the wishlist items from localStorage
  var wishlistItems = JSON.parse(localStorage.getItem("wishlist")) || [];

  // Generate HTML for wishlist items
  var wishlistHTML = "";
  wishlistItems.forEach(function(item) {
    wishlistHTML += `
      <div class="bg-white shadow-lg rounded-lg p-6 relative overflow-hidden">
        <div class="overflow-hidden">
          <img src="./Admin/${item.image}" alt="Product Image">
        </div>
        <div class="mt-4">
          <h2 class="text-xl font-semibold">${item.name}</h2>
          <p class="text-gray-500 text-sm mt-2">Weight: ${item.weight}</p>
          <div class="flex justify-between items-center mt-4">
            <span class="text-gray-600">
              <i class="fas fa-rupee-sign"></i> ${item.price}
            </span>
            <button class="text-red-500 hover:text-red-600 transition-colors duration-300 remove-btn" data-id="${item.id}" title="Remove">
              <i class="fas fa-times"></i>
            </button>
            <button class="text-blue-500 hover:text-blue-600 transition-colors duration-300" title="Add to Cart">
              <i class="fas fa-shopping-cart"></i>
            </button>
          </div>
        </div>
      </div>
    `;
  });

  // Set the HTML content of wishlistContainer
  wishlistContainer.innerHTML = wishlistHTML;

  // Add event listener to remove buttons
  var removeButtons = document.getElementsByClassName("remove-btn");
  for (var i = 0; i < removeButtons.length; i++) {
    removeButtons[i].addEventListener("click", function() {
      var itemId = this.getAttribute("data-id");
      removeItemFromWishlist(itemId);
    });
  }
});

// Function to remove an item from the wishlist
function removeItemFromWishlist(itemId) {
  var wishlistItems = JSON.parse(localStorage.getItem("wishlist")) || [];

  // Find the index of the item to be removed
  var itemIndex = wishlistItems.findIndex(function(item) {
    return item.id === itemId;
  });

  // Remove the item from the array
  if (itemIndex !== -1) {
    wishlistItems.splice(itemIndex, 1);
    localStorage.setItem("wishlist", JSON.stringify(wishlistItems));

    // Refresh the page to update the wishlist display
    location.reload();
  }
}

</script>


</body>
</html>