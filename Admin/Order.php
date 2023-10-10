<?php
require_once '../dbconnection.php'; // Include your database connection script

$date_filter = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the date from the form and convert it to "Y-m-d" format
    $input_date = $_POST["date"];
    $date_obj = DateTime::createFromFormat('d-m-Y', $input_date);
    if ($date_obj !== false) {
        $date_filter = $date_obj->format('Y-m-d');
    } else {
        echo "Invalid date format. Please use dd-mm-yyyy.";
        exit;
    }

    // Query to fetch orders filtered by date
    $sql = "SELECT * FROM `orders` WHERE DATE(`created_at`) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $date_filter);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Query to fetch all orders (no date filter)
    $sql = "SELECT `id`, `client_txn_id`, `amount`, `status`, `created_at`, `user_id`, `address_id` FROM `orders`";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Admin Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:200,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css"/>
</head>
<body>
<button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 mt-2 ml-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
   <span class="sr-only">Open sidebar</span>
   <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
      <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
   </svg>
</button>

<aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
   <div class="h-full px-3 py-4 overflow-y-auto bg-gray-50 dark:bg-gray-800">
     <a href="../index.php" class="flex items-center pl-2.5 mb-5">
         <img src="../assets/Logo/Favicon.ico" class="h-6 mr-3 sm:h-7" alt="Flowbite Logo" />
         <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">Hathibrand</span>
      </a>
      <ul class="space-y-2 font-medium">
         <li>
            <a href="./admin.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
               <svg aria-hidden="true" class="w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path></svg>
               <span class="ml-3">Dashboard</span>
            </a>
         </li>
         <li>
            <a href="./product_listing.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
               <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
               <span class="flex-1 ml-3 whitespace-nowrap">Products</span>
            </a>
         </li>
        
         <li>
            <a href="./user.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
               <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
               <span class="flex-1 ml-3 whitespace-nowrap">Users</span>
            </a>
         </li>
         <li>
            <a href="./Order.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
               <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path></svg>
               <span class="flex-1 ml-3 whitespace-nowrap">Orders</span>
            </a>
         </li>
         <li>
            <a href="./add_products.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
               <svg aria-hidden="true" class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"></path></svg>
               <span class="flex-1 ml-3 whitespace-nowrap">Add Product</span>
            </a>
         </li>
         
      </ul>
   </div>
</aside>
<div class="p-4 sm:ml-64">
   <div class="p-4  border-gray-200  rounded-lg dark:border-gray-700">
 
    <h1 class="text-3xl font-semibold mt-4 mb-8">Order Admin Page</h1>

    <form method="POST" class="mb-4">
    <div class="flex space-x-4">
        <div>
            <label for="date" class="block font-semibold">Select Date:</label>
            <input type="date" name="date" id="date" value="<?php echo $date_filter; ?>" class="border rounded-md p-2">
        </div>
        <div>
            <button type="submit" class="bg-blue-500 text-white font-semibold px-4 py-2 rounded-md">Filter by Date</button>
        </div>
    </div>
</form>

    <!-- Order Table -->
<div class="overflow-x-auto">
    <table class="min-w-full table-auto mb-8">
        <thead>
            <tr>
                <th class="px-4 py-2">Order ID</th>
                <th class="px-4 py-2">Client Transaction ID</th>
                <th class="px-4 py-2">Amount</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Created At</th>
                <th class="px-4 py-2">User ID</th>
                <th class="px-4 py-2">Address ID</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['id']}</td>";
                echo "<td>{$row['client_txn_id']}</td>";
                echo "<td>{$row['amount']}</td>";
                echo "<td>{$row['status']}</td>";
                echo "<td>{$row['created_at']}</td>";
                echo "<td>{$row['user_id']}</td>";
                echo "<td>{$row['address_id']}</td>";
                echo '<td><a href="./generate_invoice.php?orderId=' . $row['id'] . '&addressId=' . $row['address_id'] . '" target="_blank" class="bg-blue-500 text-white font-semibold px-2 py-1 my-2 rounded-md view-invoice">Download Pdf</a></td>';
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

    <!-- Invoice Section (Initially Hidden) -->
    <div id="invoice-section" class="hidden bg-white p-8 rounded-lg shadow-lg">
    <!-- Invoice content will be displayed here using AJAX -->
</div>
   </div>
</div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
// $(document).ready(function () {
//     // Handle View Invoice button click
//     $(".view-invoice").click(function () {
//         var orderId = $(this).data("order-id");

//         // Make an AJAX request to fetch and display the invoice content
//         $.ajax({
//             url: "generate_invoice.php",
//             type: "GET",
//             data: { orderId: orderId },
//             success: function () {
//                 // The PDF is generated and streamed to the browser,
//                 // so there's nothing specific to do here.
//             },
//             error: function () {
//                 alert("Error generating the invoice.");
//             },
//         });
//     });
// });

</script></body>
</html>
