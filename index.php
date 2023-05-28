<?php
    include('.\conn\dbconnection.php');
    if($conn)
    {
        echo "connection successfully";
    }
    else{
        echo "failed";
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="./assets/Logo/Favicon.ico" type="image/x-icon">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hathibrand</title>
    <script src="./styles/tailwind.css"></script>
</head>

<body>
    <nav>
        <img src="./assets/Logo/Favicon.ico" alt="">
        <ul class="">
            <li>Home</li>
            <li>Categorise</li>
            <li>Cart</li>
            <li>Login</li>
        </ul>
    </nav>
</body>

</html>