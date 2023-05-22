<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="flex flex-row justify-between items-center lg:sticky top-0 lg:px-5 px-2 bg-gray-200">
        <img class="w-24" src="./assets/Logo/Favicon.ico" alt="">
        <div class="relative">
            <input type="text" name="search" id="search" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pl-10" placeholder="Search...">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
        </div>

        <!-- Desktop navigation menu -->
        <ul class="desktop-menu text-red-500 hidden md:flex lg:flex md:text-lg flex-row gap-x-5 text-xl">
            <li class="hover:underline"><a href="">Home</a></li>
            <li class="hover:underline"><a href="">Categories</a></li>
            <li class="hover:underline"><a href="">Cart</a></li>
            <li class="hover:underline"><a href="">Login</a></li>
        </ul>

        
    </nav>

    <section class="fixed mobile-menu block lg:hidden md:hidden bottom-0 inset-x-0 z-50 shadow-lg bg-gray-700 dark:bg-dark backdrop-blur-lg bg-opacity-30 dark:bg-opacity-30 border-t-2 border-royal/20">
        <div id="tabs" class="flex justify-between">
            <a href="/" class="w-full focus:text-royal hover:text-royal justify-center inline-block text-center pt-2 pb-1 hover:bg-white">
                <div class="h-6 w-6 inline-block mb-1">
                    <i class="fa-solid text-red-500 fa-house"></i>
                </div>
                <span class="tab block text-xs font-extrabold text-yellow-600">Home</span>
            </a>
            <a href="/products" class="w-full focus:text-royal hover:text-royal justify-center inline-block text-center pt-2 pb-1 hover:bg-white">
                <div class="h-6 w-6 inline-block mb-1">
                    <i class="fa-solid text-red-500 fa-dumpster"></i>
                </div
>
                        <span class="tab block text-xs font-extrabold text-yellow-600">Categories</span>
                      </a>
                      <a href="/cart" class="w-full focus:text-royal hover:text-royal justify-center inline-block text-center pt-2 pb-1  hover:bg-white">
                        <div class="h-6 w-6 inline-block mb-1">
                            <i class="fa-solid text-red-500 fa-cart-shopping"></i>
                        </div>
                        <span class="tab block text-xs font-extrabold text-yellow-600">Cart <span>0</span></span>
                      </a>
                       
                        <a href="/login" class="w-full focus:text-royal hover:text-royal justify-center inline-block text-center pt-2 pb-1 hover:bg-white">
                            <div class="h-6 w-6 inline-block mb-1">
                                <i class="fa-solid text-red-500 fa-user"></i>
                            </div>
                          <span class="tab block text-xs font-extrabold text-yellow-600">Login</span>
                        </a>
                    </div>
                  </section>
                  

<script>
</script>

</body>
</html>