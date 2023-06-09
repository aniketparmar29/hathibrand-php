<?php
// Start the session
session_start();

// Check if the session message exists
if (isset($_SESSION['msg'])) {
    // Get the session message
    $message = $_SESSION['msg'];

    // Clear the session message
    unset($_SESSION['msg']);

    // Return the message as the response
    echo $message;
} else {
    // If no message exists, return an empty response
    echo '';
}
?>
