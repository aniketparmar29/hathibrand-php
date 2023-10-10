<?php
require_once './dbconnection.php'; // Include your database connection script

// Initialize the message variable
$message = '';

// Check if the user has an existing address
$user_id = $_COOKIE['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['saveAddress'])) {
        // Save or update User Address
        $name = $_POST['name'];
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];
        $alt_mobile = $_POST['alt_mobile'];
        $district = $_POST['district'];
        $taluka = $_POST['taluka'];
        $village = $_POST['village'];
        $address = $_POST['address'];
        $pincode = $_POST['pincode'];

        if (empty($_POST['address_id'])) {
            // Insert new address
            $sql = "INSERT INTO `user_addresses` (`user_id`, `name`, `mobile`, `email`, `alt_mobile`, `district`, `taluka`, `village`, `address`, `pincode`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssssssss", $user_id, $name, $mobile, $email, $alt_mobile, $district, $taluka, $village, $address, $pincode);
        } else {
            // Update existing address
            $address_id = $_POST['address_id'];
            $sql = "UPDATE `user_addresses` 
                SET `name` = ?, `mobile` = ?, `email` = ?, `alt_mobile` = ?, `district` = ?, `taluka` = ?, `village` = ?, `address` = ?, `pincode` = ?
                WHERE `user_id` = ? AND `id` = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssssssi", $name, $mobile, $email, $alt_mobile, $district, $taluka, $village, $address, $pincode, $user_id, $address_id);
        }

        if ($stmt->execute()) {
            $message = "Address saved successfully!";
        } else {
            $message = "Error saving the address: " . $stmt->error;
        }
        $stmt->close();
    } elseif (isset($_POST['deleteAddress'])) {
        // Delete User Address
        $address_id = $_POST['address_id'];

        // Prepare and execute the SQL query to delete the address
        $sql = "DELETE FROM `user_addresses` WHERE `user_id` = ? AND `id` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $address_id);

        if ($stmt->execute()) {
            $message = "Address deleted successfully!";
        } else {
            $message = "Error deleting the address: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Return a JSON response
$response = array("message" => $message);
echo json_encode($response);
?>
