<?php
require_once './dbconnection.php'; // Include your database connection script

// Initialize the message variable
$message = '';

// Check if the user has an existing address
$user_id = $_COOKIE['user_id'];
$existingAddress = null;

$sql = "SELECT `id`, `user_id`, `name`, `mobile`, `email`, `alt_mobile`, `district`, `taluka`, `village`, `address`, `pincode` 
        FROM `user_addresses` 
        WHERE `user_id` = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Address exists, fetch the data
    $existingAddress = $result->fetch_assoc();
}

// Check if the form was submitted
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Address CRUD</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.7/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-xl mx-auto bg-white rounded-lg p-6 shadow">
        <h1 class="text-2xl font-semibold mb-4">Address CRUD</h1>
        
        <!-- Display a message if any -->
        <?php if (!empty($message)) : ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?= $message ?></span>
            </div>
        <?php endif; ?>

        <?php if ($existingAddress !== null) : ?>
            <!-- Address Edit and Delete Options -->
            <h2 class="text-xl font-semibold mb-4">Edit Address</h2>
            <!-- Display existing address data here and provide edit and delete options -->
            <form action="your_controller.php" method="POST" class="space-y-4">
                <input type="hidden" name="address_id" value="<?= $existingAddress['id'] ?>">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" value="<?= $existingAddress['name'] ?>" class="mt-1 p-2 block w-full rounded-md border border-gray-300">
                    </div>
                    <div>
                        <label for="mobile" class="block font-medium text-gray-700">Mobile</label>
                        <input type="text" name="mobile" id="mobile" value="<?= $existingAddress['mobile'] ?>" class="mt-1 p-2 block w-full rounded-md border border-gray-300">
                    </div>
                    <div>
                        <label for="email" class="block font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="<?= $existingAddress['email'] ?>" class="mt-1 p-2 block w-full rounded-md border border-gray-300">
                    </div>
                    <div>
                        <label for="alt_mobile" class="block font-medium text-gray-700">Alternate Mobile</label>
                        <input type="text" name="alt_mobile" id="alt_mobile" value="<?= $existingAddress['alt_mobile'] ?>" class="mt-1 p-2 block w-full rounded-md border border-gray-300">
                    </div>
                    <div>
                        <label for="district" class="block font-medium text-gray-700">District</label>
                        <input type="text" name="district" id="district" value="<?= $existingAddress['district'] ?>" class="mt-1 p-2 block w-full rounded-md border border-gray-300">
                    </div>
                    <div>
                        <label for="taluka" class="block font-medium text-gray-700">Taluka</label>
                        <input type="text" name="taluka" id="taluka" value="<?= $existingAddress['taluka'] ?>" class="mt-1 p-2 block w-full rounded-md border border-gray-300">
                    </div>
                    <div>
                        <label for="village" class="block font-medium text-gray-700">Village</label>
                        <input type="text" name="village" id="village" value="<?= $existingAddress['village'] ?>" class="mt-1 p-2 block w-full rounded-md border border-gray-300">
                    </div>
                    <div>
                        <label for="address" class="block font-medium text-gray-700">Address</label>
                        <textarea name="address" id="address" rows="4" class="mt-1 p-2 block w-full rounded-md border border-gray-300"><?= $existingAddress['address'] ?></textarea>
                    </div>
                    <div>
                        <label for="pincode" class="block font-medium text-gray-700">Pincode</label>
                        <input type="text" name="pincode" id="pincode" value="<?= $existingAddress['pincode'] ?>" class="mt-1 p-2 block w-full rounded-md border border-gray-300">
                    </div>
                </div>
                <!-- Add edit and delete buttons here -->
            </form>
        <?php else : ?>
            <!-- Address Add Form -->
            <h2 class="text-xl font-semibold mb-4">Add Address</h2>
            <form action="your_controller.php" method="POST" class="space-y-4">
                <input type="hidden" name="address_id" value="">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" class="mt-1 p-2 block w-full rounded-md border border-gray-300">
                    </div>
                    <div>
                        <label for="mobile" class="block font-medium text-gray-700">Mobile</label>
                        <input type="text" name="mobile" id="mobile" class="mt-1 p-2 block w-full rounded-md border border-gray-300">
                    </div>
                    <div>
                        <label for="email" class="block font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" class="mt-1 p-2 block w-full rounded-md border border-gray-300">
                    </div>
                    <div>
                        <label for="alt_mobile" class="block font-medium text-gray-700">Alternate Mobile</label>
                        <input type="text" name="alt_mobile" id="alt_mobile" class="mt-1 p-2 block w-full rounded-md border border-gray-300">
                    </div>
                    <div>
                        <label for="district" class="block font-medium text-gray-700">District</label>
                        <input type="text" name="district" id="district" class="mt-1 p-2 block w-full rounded-md border border-gray-300">
                    </div>
                    <div>
                        <label for="taluka" class="block font-medium text-gray-700">Taluka</label>
                        <input type="text" name="taluka" id="taluka" class="mt-1 p-2 block w-full rounded-md border border-gray-300">
                    </div>
                    <div>
                        <label for="village" class="block font-medium text-gray-700">Village</label>
                        <input type="text" name="village" id="village" class="mt-1 p-2 block w-full rounded-md border border-gray-300">
                    </div>
                    <div>
                        <label for="address" class="block font-medium text-gray-700">Address</label>
                        <textarea name="address" id="address" rows="4" class="mt-1 p-2 block w-full rounded-md border border-gray-300"></textarea>
                    </div>
                    <div>
                        <label for="pincode" class="block font-medium text-gray-700">Pincode</label>
                        <input type="text" name="pincode" id="pincode" class="mt-1 p-2 block w-full rounded-md border border-gray-300">
                    </div>
                </div>
                <!-- Add a button to save the new address -->
                <div class="flex items-center">
                    <button type="submit" name="saveAddress" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Save Address</button>
                </div>
            </form>
        <?php endif; ?>

        <!-- Address List -->
        <ul class="mt-8 space-y-4">
            <?php if (isset($addresses) && is_array($addresses)) : ?>
                <?php foreach ($addresses as $address) : ?>
                    <li class="bg-gray-50 p-4 border border-gray-200 rounded-lg">
                        <span class="text-lg font-semibold"><?= $address['name'] ?></span>
                        <p><?= $address['address'] ?></p>
                        
                        <!-- Add edit and delete buttons with appropriate form -->
                        <form action="your_controller.php" method="POST" class="mt-2">
                            <input type="hidden" name="address_id" value="<?= $address['id'] ?>">
                            <button type="submit" name="editAddress" class="px-2 py-1 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Edit</button>
                            <button type="submit" name="deleteAddress" class="px-2 py-1 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Delete</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            <?php else : ?>
                <p>No addresses found.</p>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>
