<?php
// Include the database connection file
include('dbconnection.php');

// Start a session
// session_start(); 

// Check if the user clicked the signup button
if (isset($_POST['submit'])) {
    // Retrieve the user's input
    $username = $_POST['username'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];

    // Check if email already exists
    $check_email_query = "SELECT email FROM user WHERE email = '$email'";
    $check_email_query_run = mysqli_query($conn, $check_email_query);

    if (mysqli_num_rows($check_email_query_run) > 0) {
        $_SESSION['error'] = "Email already exists";
        header('Location: signUp.php');
        exit();
    } else {
        $insert_query = "INSERT INTO user (username, email, mobile, password) VALUES ('$username', '$email', '$mobile', '$password')";
        $insert_query_run = mysqli_query($conn, $insert_query);

        if ($insert_query_run) {
            $_SESSION['success'] = "Account created successfully!";
            header('Location: login.php');
            exit();
        } else {
            $_SESSION['error'] = "Error creating account";
            header('Location: signUp.php');
            exit();
        }
    }
}

// Check if the user clicked the Google login button
if (isset($_POST['google_login'])) {
    // Redirect the user to the Google login page
    header("Location: google_login.php");
    exit();
}

// Check if the user clicked the Facebook login button
if (isset($_POST['facebook_login'])) {
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
        .container {
            top: 200px;
        }

        .imgop {
            height: 120vh;
            width: 100%;
        }
    </style>


</head>

<body>
    <?php include './components/Navbar.php' ?>
    <div class="container">
        <div class="max-w-md  bg-white p-6 rounded-md shadow-md">
            <h2 class="text-2xl mb-4">Login</h2>
            <form method="POST" action="">
                <div class="mb-4">
                    <label class="block mb-2">Username:</label>
                    <input type="text" name="username" id="username" class="border rounded px-4 py-2 w-full" required>
                </div>
                <div class="mb-4">
                    <label class="block mb-2">Email</label>
                    <input type="email" name="email" id="email" class="border rounded px-4 py-2 w-full" required>
                    <?php
                    if (isset($_SESSION['error'])) {
                    ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Hey </strong><?= $_SESSION['error']; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php
                        unset($_SESSION['error']);
                    }
                    ?>
                </div>
                <div class="mb-4">
                    <label class="block mb-2">Mobile</label>
                    <input type="text" name="mobile" id="mobile" class="border rounded px-4 py-2 w-full" required>
                </div>
                <div class="mb-4">
                    <label class="block mb-2">Password</label>
                    <input type="password" name="password" id="password" class="border rounded px-4 py-2 w-full" required>
                </div>
                <button type="submit" name="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Sign Up</button>
                <div class="flex items-center justify-between mt-4">
                    <a href="login.php" name="signup" class="text-blue-500">Login</a>
                    <div>
                        <div class="mt-2">
                        </div>
                    </div>
                </div>
            </form>
            <span>or sign up with:</span>
            <button type="submit" name="google_login" class="bg-red-500 text-white px-4 py-2 rounded">Google</button>
            <button type="submit" name="facebook_login" class="bg-blue-500 text-white px-4 py-2 rounded">Facebook</button>
        </div>
    </div>
    <?php include './components/Footer.php' ?>
</body>

</html>