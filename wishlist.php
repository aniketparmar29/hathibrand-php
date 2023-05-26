<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Head content -->
    <title>Wishlist</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="shortcut icon" href="./assets/Logo/Favicon.ico" type="image/x-icon">
</head>
<body>
    <!-- Navbar HTML -->
    <?php include './components/Navbar.php'?>

    <!-- Wishlist Page HTML -->
    <h1 class="text-2xl text-center">Wishlist</h1>
    <div class="container mx-auto">
        <div id="wishlist-container" class="grid gap-8 grid-cols-2 lg:grid-cols-4"></div>
    </div>
    
    <!-- Footer HTML -->
    <?php include './components/Footer.php'?>

    <!-- JavaScript code -->
    <script>
    // Wishlist Page JavaScript
document.addEventListener("DOMContentLoaded", function() {
  var wishlistContainer = document.getElementById("wishlist-container");

  // Retrieve the wishlist items from localStorage
  var wishlistItems = JSON.parse(localStorage.getItem("wishlist")) || [];

  // Generate HTML for wishlist items
  wishlistItems.forEach(function(item) {
    var wishlistItem = document.createElement("div");
    wishlistItem.className = "bg-white shadow-lg rounded-lg p-6 relative overflow-hidden";

    var imageContainer = document.createElement("div");
    imageContainer.className = "overflow-hidden";

    var image = document.createElement("img");
    image.src = "./Admin/" + item.image;
    image.alt = "Product Image";

    var itemDetails = document.createElement("div");
    itemDetails.className = "mt-4";

    var itemName = document.createElement("h2");
    itemName.className = "text-xl font-semibold";
    itemName.textContent = item.name;

    var itemWeight = document.createElement("p");
    itemWeight.className = "text-gray-500 text-sm mt-2";
    itemWeight.textContent = "Weight: " + item.weight;

    var itemPrice = document.createElement("div");
    itemPrice.className = "flex justify-between items-center mt-4";

    var price = document.createElement("span");
    price.className = "text-gray-600";
    price.innerHTML = "<i class='fas fa-rupee-sign'></i> " + item.price;

    var removeButton = document.createElement("button");
    removeButton.className = "text-red-500 hover:text-red-600 transition-colors duration-300 remove-btn";
    removeButton.setAttribute("data-id", item.id);
    removeButton.title = "Remove";
    removeButton.innerHTML = "<i class='fas fa-times'></i>";

    var cartButton = document.createElement("button");
    cartButton.className = "text-blue-500 hover:text-blue-600 transition-colors duration-300";
    cartButton.title = "Add to Cart";
    cartButton.innerHTML = "<i class='fas fa-shopping-cart'></i>";

    itemPrice.appendChild(price);
    itemPrice.appendChild(removeButton);
    itemPrice.appendChild(cartButton);

    itemDetails.appendChild(itemName);
    itemDetails.appendChild(itemWeight);
    itemDetails.appendChild(itemPrice);

    imageContainer.appendChild(image);

    wishlistItem.appendChild(imageContainer);
    wishlistItem.appendChild(itemDetails);

    wishlistContainer.appendChild(wishlistItem);
  });

  // Add event listener to the wishlist container using event delegation
  wishlistContainer.addEventListener("click", function(event) {
    var removeButton = event.target.closest(".remove-btn");
    if (removeButton) {
      var itemId = removeButton.getAttribute("data-id");
      removeItemFromWishlist(itemId);
    }
  });
});

// Function to remove an item from the wishlist
function removeItemFromWishlist(itemId) {
  var wishlistItems = JSON.parse(localStorage.getItem("wishlist")) || [];

  // Find the index of the item to be removed
  var itemIndex = wishlistItems.findIndex(function(item) {
    return item.id === itemId;
  });
  // Remove the item from the array
  if (itemIndex === -1) {
    wishlistItems.splice(itemIndex, 1);
    localStorage.setItem("wishlist", JSON.stringify(wishlistItems));
    location.reload();

    // Refresh the page to update the wishlist display
  }
}

    </script>
</body>
</html>