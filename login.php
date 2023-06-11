<?php
include('./dbconnection.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];

    // Check if the mobile and password match in the database
    $selectQuery = "SELECT * FROM user WHERE mobile = '$mobile'";
    $result = mysqli_query($conn, $selectQuery);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $storedPassword = $row['password'];

        // Verify the password
        if (password_verify($password, $storedPassword)) {
            // Password is correct, store user data in session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['auth']=true;
            $_SESSION['msg']="Login Successful";

            // Redirect to the index page
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid mobile or password";
        }
    } else {
        $error = "Invalid mobile or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hathibrand</title>
    <link rel="shortcut icon" href="./assets/Logo/Favicon.ico" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<style>
    
    .bg-image {
            width: 100%;
            height: 100%;
            background-image: url('./assets/bg-images/Login.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            
        }
       
</style>

<body>
    <?php require './components/Navbar.php' ?>
    <section class="bg-image bg-gray-50 dark:bg-gray-900">
        <div  class=" flex flex-col   items-center justify-center transition duration-500 lg:items-start  px-6 py-8 mx-auto md:h-screen lg:py-0">

            <div class="w-full  md:mt-0 sm:max-w-md xl:p-0">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-xl font-bold leading-tight tracking-tight  md:text-2xl text-white">
                        Login
                    </h1>
                    <?php
                    if (isset($error)) {
                        echo '<p class="text-red-500">' . $error . '</p>';
                    }
                    ?>
                    <form class="space-y-4 md:space-y-6" method="POST" action="#">
                        <div>
                            <label for="mobile" class="block mb-2 text-sm font-medium text-white">Mobile</label>
                            <input type="mobile" name="mobile" id="mobile" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="0123456789" required>
                        </div>
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-white">Password</label>
                            <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                        </div>

                        <button type="submit" class="w-full text-white bg-red-500 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Login</button>
                        <p class="text-sm font-light text-white">
                            Create an account? <a href="./singup.php" class="font-medium text-red-500 hover:underline">Signup</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <?php require './components/Footer.php' ?>

</body>

</html>
