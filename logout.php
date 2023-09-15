<?php
session_start();
// Clear the authentication cookies
setcookie('auth', '', time() - 3600, '/');
setcookie('user_id', '', time() - 3600, '/');
setcookie('username', '', time() - 3600, '/');
setcookie('role', '', time() - 3600, '/');
$_SESSION['msg']="Logout Successful";
header('Location:./index.php');
?>
