<?php
    include('dbconnection.php');

    // Get sorting and filtering options from the query parameters
    $sort = isset($_GET['sort']) ? $_GET['sort'] : ''; // Sorting option: price_htl, price_lth, weight_htl, weight_lth
    $category = isset($_GET['category']) ? $_GET['category'] : ''; // Filtering option: category

    // Construct the SQL query based on the sorting and filtering options
    $sql = "SELECT * FROM products";
    if ($category !== '') {
        $sql .= " WHERE category = '$category'";
    }

    $order = '';
    if ($sort === 'price_htl') {
        $order = 'ORDER BY price DESC';
    } elseif ($sort === 'price_lth') {
        $order = 'ORDER BY price ASC';
    } elseif ($sort === 'weight_htl') {
        $order = 'ORDER BY weight DESC';
    } elseif ($sort === 'weight_lth') {
        $order = 'ORDER BY weight ASC';
    }

    if ($order !== '') {
        $sql .= " $order";
    }

    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.7/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <?php include './components/Navbar.php'?>

    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Products</h1>

        <div class="flex mb-4">
            <div class="mr-2">
                <label for="sort">Sort by:</label>
                <select id="sort" name="sort" onchange="location = this.value;">
                    <option value="">None</option>
                    <option value="?sort=price_htl">Price: High to Low</option>
                    <option value="?sort=price_lth">Price: Low to High</option>
                    <option value="?sort=weight_htl">Weight: High to Low</option>
                    <option value="?sort=weight_lth">Weight: Low to High</option>
                </select>
            </div>
            <div>
                <label for="category">Filter by category:</label>
                <select id="category" name="category" onchange="location = this.value;">
                    <option value="">All</option>
                    <option value="?category=Agarbatti">Agarbatti</option>
                    <option value="?category=Cosmetic">Cosmetics</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $productName = $row["name"];
                    $productImage = $row["image"];
                    $productPrice = $row["price"];
                    if ($row["stock"] > 0) {
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
    <?php include './components/Footer.php'?>

</body>
</html>
