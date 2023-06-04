<?php
session_start();

if(isset($_SESSION['auth']))
{
    unset($_SESSION['auth']);
    unset($_SESSION['username']);
    unset($_SESSION['user_id']);
    unset($_SESSION['role']);
    $_SESSION['message']="Logged out Successfully";
}
header('Location:./index.php');
?>