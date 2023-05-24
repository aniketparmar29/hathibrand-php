
<!DOCTYPE html>
<html>
<head>
    <title>Products Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.7/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>

<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Products</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <?php
      

        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $productName = $row["name"];
                $productImage = $row["image"];
                $productPrice = $row["price"];
                if($row["stock"]>0){
                ?>
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <img src="<?php echo $productImage; ?>" alt="<?php echo $productName; ?>"
                             class="object-contain h-48 w-full mb-4">
                        <h2 class="text-lg font-bold mb-2"><?php echo $productName; ?></h2>
                        <p class="text-gray-600">$<?php echo $productPrice; ?></p>
                    </div>
                    
                    <?php
                }
            }
        } else {
            echo "No products found.";
        }

        $conn->close();
        ?>
    </div>
</div>

</body>
</html>
