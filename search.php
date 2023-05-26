<?php
// Establish database connection
$connection = mysqli_connect("89.117.188.52","u303467217_aniket_akki","close777@A","u303467217_hathibrand_php");

// Retrieve search query from AJAX request
$query = strtolower($_POST['query']);

// Construct SQL query
$sql = "SELECT * FROM products WHERE LOWER(name) LIKE '%$query%'";

// Execute the query
$result = mysqli_query($connection, $sql);

// Check if the query was successful
if (!$result) {
    $error = mysqli_error($connection);
    $response = array('error' => $error);
    echo json_encode($response);
    exit();
}

// Fetch search results into an array
$resultsArray = [];
while ($row = mysqli_fetch_assoc($result)) {
    $resultsArray[] = $row;
}

// Close the database connection
mysqli_close($connection);

// Check if any results were found
if (empty($resultsArray)) {
    $response = array('message' => 'No results found.');
    echo json_encode($response);
    exit();
}

// Suppress any unwanted output
ob_end_clean();

// Return the search results as JSON
header('Content-Type: application/json');
echo json_encode($resultsArray);
exit();
?>
