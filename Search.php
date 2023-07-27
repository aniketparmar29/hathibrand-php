<?php 
session_start();
// Fetch categories from the database

include('dbconnection.php');

if (isset($_GET['term'])) {
    $searchTerm = $_GET['term'];

    // Use prepared statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT * FROM categories WHERE name LIKE ?");
    $searchTerm = "%" . $searchTerm . "%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();

    $result = $stmt->get_result();

    $categories = array();
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }

    $stmt->close();
}

// Close the database connection
$conn->close();

// Return the categories as JSON
header('Content-Type: application/json');
echo json_encode($categories);
?>
