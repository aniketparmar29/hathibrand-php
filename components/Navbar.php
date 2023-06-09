
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
    <input type="text" name="search" id="search" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pl-10" placeholder="Search...">
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <i class="fa-solid fa-magnifying-glass" id="searchIcon"></i>
    </div>
</div>

        <!-- Desktop navigation menu -->
        <ul class="desktop-menu text-red-500 hidden md:flex lg:flex md:text-lg flex-row gap-x-5 text-xl">
            <li class="hover:underline"><a href="index.php">Home</a></li>
            <li class="hover:underline"><a href="Products.php">Categories</a></li>
            <li class="hover:underline"><a href="cart.php"><i class="fa fa-shopping-cart"></i></a></li>
            <li class="hover:underline"><a href="wishlist.php"><i class="fa fa-heart"></i></a></li>
            <?php
              if (isset($_SESSION['auth'])) {
              ?>
               
               
<button id="dropdownHoverButton" data-dropdown-toggle="dropdownHover" data-dropdown-trigger="hover" class="flex flex-row gap-x-1 items-center" type="button"><p class="nav-item"> <?= $_SESSION['username']; ?></p><i class="fa-solid fa-caret-down ps-1"></i></button>
<!-- Dropdown menu -->
<div id="dropdownHover" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownHoverButton">
      <li>
        <a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">My Order</a>
      </li>
      <?php
if (isset($_SESSION['role']) && $_SESSION['role'] === "admin") {
?>
  <li>
    <a href="./Admin/admin.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Admin</a>
  </li>
<?php 
}
?>

      <li>
        <a href="./logout.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Logout</a>
      </li>
    </ul>
</div>

               <?php
              } else {
              ?>
            <li class="hover:underline"><a href="login.php">Login</a></li>
            <?php
              }
              ?>
        </ul>
    </nav>

    
<div id="search-results"> </div>
    <section class="fixed mobile-menu block lg:hidden md:hidden bottom-0 inset-x-0 z-50 shadow-lg bg-white dark:bg-dark  border-t-2 border-royal/20">
        <div id="tabs" class="flex justify-between">
            <a href="index.php" class="w-full focus:text-royal hover:text-royal justify-center inline-block text-center pt-2 pb-1 hover:bg-gray-200">
                <div class="h-6 w-6 inline-block mb-1">
                    <i class="fa-solid text-red-500 fa-house"></i>
                </div>
                <span class="tab block text-xs font-extrabold text-yellow-600">Home</span>
            </a>
            <a href="Products.php" class="w-full focus:text-royal hover:text-royal justify-center inline-block text-center pt-2 pb-1 hover:bg-gray-200">
                <div class="h-6 w-6 inline-block mb-1">
                    <i class="fa-solid text-red-500 fa-dumpster"></i>
                </div
>
                        <span class="tab block text-xs font-extrabold text-yellow-600">Categories</span>
                      </a>
                      <a href="cart.php" class="w-full focus:text-royal hover:text-royal justify-center inline-block text-center pt-2 pb-1  hover:bg-gray-200">
                        <div class="h-6 w-6 inline-block mb-1">
                            <i class="fa-solid text-red-500 fa-cart-shopping"></i>
                        </div>
                        <span class="tab block text-xs font-extrabold text-yellow-600">Cart <span>0</span></span>
                      </a>
                      <?php
if (isset($_SESSION['auth'])) {
?>
<button  class=" gap-x-1 items-center w-full focus:text-royal hover:text-royal justify-center inline-block text-center pt-2 pb-1 hover:bg-gray-200" type="button" onclick="openDropdown(event,'dropdown-id')">
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
        <?php
        if (isset($_SESSION['role']) && $_SESSION['role'] === "admin") {
        ?>
            <li>
                <a href="./Admin/admin.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Admin</a>
            </li>
        <?php 
        }
        ?>
        <li>
            <a href="./logout.php" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Logout</a>
        </li>
    </ul>
</div>
<?php
              } else {
              ?>
                        <a href="login.php" class="w-full focus:text-royal hover:text-royal justify-center inline-block text-center pt-2 pb-1 hover:bg-gray-200">
                            <div class="h-6 w-6 inline-block mb-1">
                                <i class="fa-solid text-red-500 fa-user"></i>
                            </div>

                            
                          <span class="tab block text-xs font-extrabold text-yellow-600">Login</span>
                        </a>
                        <?php
              }
              ?>
                    </div>
                  </section>
                  
                  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                  <script>
var timer = null;
var searchInput = document.getElementById('search');
var searchResults = document.getElementById('search-results');

searchInput.addEventListener('keyup', function() {
  clearTimeout(timer);
  timer = setTimeout(makeRequest, 300);
});

searchInput.addEventListener('input', function() {
  clearTimeout(timer);
  timer = setTimeout(makeRequest, 300);
});

function makeRequest() {
  var query = searchInput.value.trim();
  if (query !== '') {
    var request = new XMLHttpRequest();
    request.open('POST', './search.php', true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    request.onreadystatechange = function() {
      if (request.readyState === 4 && request.status === 200) {
        var results = JSON.parse(request.responseText);
        var output = '';
        for (var i = 0; i < results.length; i++) {
          output += '<div>';
          output += '<h3>' + results[i].name + '</h3>';
          output += '<p>' + results[i].description + '</p>';
          output += '</div>';
        }
        searchResults.innerHTML = output;
      }
    };
    request.send('query=' + encodeURIComponent(query.toLowerCase()));
  } else {
    searchResults.innerHTML = '';
  }
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

</script>

</body>
</html>
<script src="https://unpkg.com/@popperjs/core@2.9.1/dist/umd/popper.min.js" charset="utf-8"></script>
