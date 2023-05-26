<?php
    // Include the database connection file
    include('dbconnection.php');
    
    // Check if the user clicked the submit button
    if(isset($_POST['submit'])) {
        // Retrieve the user's input
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        // Perform validation and authentication
        // ... Your code for validation and authentication goes here ...
        
        // Redirect the user to the home page after successful login
        header("Location: home.php");
        exit();
    }
    
    // Check if the user clicked the signup button
    if(isset($_POST['signup'])) {
        // Retrieve the user's input
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        // Perform validation and create a new user account
        // ... Your code for validation and account creation goes here ...
        
        // Redirect the user to the login page after successful signup
        header("Location: login.php");
        exit();
    }
    
    // Check if the user clicked the Google login button
    if(isset($_POST['google_login'])) {
        // Redirect the user to the Google login page
        header("Location: google_login.php");
        exit();
    }
    
    // Check if the user clicked the Facebook login button
    if(isset($_POST['facebook_login'])) {
        // Redirect the user to the Facebook login page
        header("Location: facebook_login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="./assets/Logo/Favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login-Hathibrand</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .container{
            top:200px;
        }
        .imgop{
            height:120vh;
            width: 100%;
        }
</style>


</head>
<body >
    <?php include './components/Navbar.php'?>
    <div class="container">
        <div class="max-w-md  bg-white p-6 rounded-md shadow-md">
            <h2 class="text-2xl mb-4">Login</h2>
            <form method="POST" action="">
                <div class="mb-4">
                    <label for="email" class="block mb-2">Email:</label>
                    <input type="email" name="email" id="email" class="border rounded px-4 py-2 w-full" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block mb-2">Password:</label>
                    <input type="password" name="password" id="password" class="border rounded px-4 py-2 w-full" required>
                </div>
                <button type="submit" name="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Login</button>
                <div class="flex items-center justify-between mt-4">
                <a href="SingUp.php" name="signup" class="text-blue-500">Sign Up</a>
                        <div>
                            <div class="mt-2">
                                </div>
                            </div>
                        </div>
                    </form>
                    <span>or login with:</span>
                <button type="submit" name="google_login" class="bg-red-500 text-white px-4 py-2 rounded">Google</button>
                <button type="submit" name="facebook_login" class="bg-blue-500 text-white px-4 py-2 rounded">Facebook</button>
                        </div>
                        </div>
                        <?php include './components/Footer.php'?>
                        </body>
                        </html>