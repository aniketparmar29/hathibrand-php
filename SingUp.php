<?php
include('./dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];
    
    // Check if mobile or email already exists in the database
    $checkQuery = "SELECT * FROM user WHERE mobile = '$mobile' OR email = '$email'";
    $result = mysqli_query($conn, $checkQuery);
    if (mysqli_num_rows($result) > 0) {
        $error = "Mobile or email already exists";
    } else {    
        // Encrypt the password before storing in the database
        $encryptedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new user into the database
        $insertQuery = "INSERT INTO user (username, email, mobile, password, role) VALUES ('$username', '$email', '$mobile', '$encryptedPassword', 'user')";
        // ...
        if (mysqli_query($conn, $insertQuery)) {
            // Redirect to the login page
            header("Location: login.php");
            exit();
        } else {
            $error = "Error occurred while creating an account: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Hathibrand</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="./assets/Logo/Favicon.ico" type="image/x-icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    .bg-container {
        background-image: url('./assets/bg-images/Singup.jpg');
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
    }
    </style>
</head>

<body>
    <?php require './components/Navbar.php' ?>
    <section class="bg-container  dark:bg-gray-900">
        <div class="flex flex-col items-center justify-center  lg:items-end px-3 py-4 mx-auto md:h-screen lg:py-0">
            <div class="w-full  rounded-lg   md:mt-0 sm:max-w-md xl:p-0 ">
                <div class=" sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-white md:text-2xl dark:text-white">
                        Create an account
                    </h1>
                    <?php
                    if (isset($error)) {
                        echo '<p class="text-red-500">' . $error . '</p>';
                    }
                    ?>
                    <form class="space-y-2 md:space-y-4" method="POST" action="#">
                        <div>
                            <label for="username" class="block mb-2 text-sm font-medium text-white dark:text-white">Username</label>
                            <input type="text" name="username" id="username" class=" border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Username" required>
                        </div>
                        <div>
                            <label for="email" class="block mb-2 text-sm font-medium text-white dark:text-white">Your email</label>
                            <input type="email" name="email" id="email" class=" border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" required>
                        </div>
                        <div>
                            <label for="mobile" class="block mb-2 text-sm font-medium text-white dark:text-white">Mobile</label>
                            <input type="number" name="mobile" id="mobile" class=" border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="0123456789" required>
                        </div>
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-white dark:text-white">Password</label>
                            <input type="password" name="password" id="password" placeholder="••••••••" class=" border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        </div>

                        <button type="submit" class="w-full text-white bg-red-500 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Create an account</button>
                        <p class="text-sm font-light text-white ">
                            Already have an account? <a href="./login.php" class="font-medium text-red-500 hover:underline">Login here</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <?php require './components/Footer.php' ?>
</body>

</html>
