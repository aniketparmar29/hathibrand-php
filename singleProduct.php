

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="./assets/Logo/Favicon.ico" type="image/x-icon">

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

      
        $productName = $row['product_name'];
      
        
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
            <i class="fas fa-rupee-sign"></i> <p id="priceop"> <?php echo $productPrice; ?></p>
        </span>
        <div class="flex flex-col justify-between items-center mt-4 gap-y-5">
            
               
                <button  onclick="addToCart(<?php echo $productID; ?>, '<?php echo $productName; ?>', '<?php echo $productImage[0]; ?>', '<?php echo $productWeight; ?>', '<?php echo $productPrice; ?>')" class="text-white bg-yellow-500 rounded-lg p-4 hover:text-blue-600 transition-colors duration-300 flex flex-row justify-between w-40" title="Add to Cart">
                    <span> Add To Cart</span><i class="fas fa-shopping-cart text-2xl "></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
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
