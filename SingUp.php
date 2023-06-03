
<?php
require_once 'dbconnection.php'; // Include the file with the database connection

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $mobile = $_POST["mobile"];
    $password = $_POST["password"];

    // Validate mobile number
    if (!preg_match("/^\d{10}$/", $mobile)) {
        die("Error: Mobile number should have 10 digits.");
    }

    // Encrypt the password (you can use any suitable encryption method)
    $encryptedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if the email is already registered
    $checkQuery = "SELECT * FROM `user` WHERE `email` = '$email'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        die("Error: Email is already registered.");
    }

    // Prepare and execute the query
    $query = "INSERT INTO `user`(`username`, `role`, `email`, `mobile`, `password`, `created_at`) 
              VALUES ('$username', 'user', '$email', '$mobile', '$encryptedPassword', NOW())";
    $result = mysqli_query($conn, $query);

    // Check if the query was successful
    if ($result) {
        echo "Signup successful!";
    } else {
        echo "Error occurred while signing up.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup-Hathibrand</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.7/dist/tailwind.min.css" rel="stylesheet">

</head>
<body>
<?php include './components/Navbar.php'?>

    <div class="container mx-auto p-12 shadow-lg shadow-red-200">
        <h2 class="text-2xl font-bold mb-4 text-center">Signup Form</h2>
        <form method="POST" action="signup.php" class="max-w-sm mx-auto">
            <input type="text" name="username" placeholder="Username" required class="w-full px-4 py-2 rounded border-gray-300 mb-4 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
            <input type="email" name="email" placeholder="Email" required class="w-full px-4 py-2 rounded border-gray-300 mb-4 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
            <input type="text" name="mobile" placeholder="Mobile (10 digits)" required class="w-full px-4 py-2 rounded border-gray-300 mb-4 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
            <input type="password" name="password" placeholder="Password" required class="w-full px-4 py-2 rounded border-gray-300 mb-4 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
            <button type="submit" class="w-full bg-red-500 text-white py-2 rounded hover:bg-red-600">Signup</button>
        </form>
    </div>
<?php include './components/Footer.php'?>

</body>
</html>
