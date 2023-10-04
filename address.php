<?php
require_once './dbconnection.php'; // Include your database connection script

// Initialize the message variable
$message = '';

// Check if the user has an existing address
$user_id = $_COOKIE['user_id'];
$sql = "SELECT * FROM `user_addresses` WHERE `user_id` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User has an existing address, fetch address details
    $addressData = $result->fetch_assoc();
} else {
    // User does not have an address, initialize an empty array
    $addressData = array();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['saveAddress'])) {
        // Save or update User Address
        $name = $_POST['name'];
        $mobile = $_POST['mobile'];
        $alt_mobile = $_POST['alt_mobile'];
        $district = $_POST['district'];
        $taluka = $_POST['taluka'];
        $village = $_POST['village'];
        $address = $_POST['address'];
        $pincode = $_POST['pincode'];

        if (empty($addressData)) {
            // Insert new address
            $sql = "INSERT INTO `user_addresses` (`user_id`, `name`, `mobile`, `alt_mobile`, `district`, `taluka`, `village`, `address`, `pincode`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        } else {
            // Update existing address
            $sql = "UPDATE `user_addresses` 
                SET `name` = ?, `mobile` = ?, `alt_mobile` = ?, `district` = ?, `taluka` = ?, `village` = ?, `address` = ?, `pincode` = ?
                WHERE `user_id` = ?";
        }

        $stmt = $conn->prepare($sql);
        if (empty($addressData)) {
            $stmt->bind_param("isssssssi", $user_id, $name, $mobile, $alt_mobile, $district, $taluka, $village, $address, $pincode);
        } else {
            $stmt->bind_param("ssssssssi", $name, $mobile, $alt_mobile, $district, $taluka, $village, $address, $pincode, $user_id);
        }

        if ($stmt->execute()) {
            $message = "Address saved successfully!";
        } else {
            $message = "Error saving the address: " . $stmt->error;
        }
        $stmt->close();
    } elseif (isset($_POST['deleteAddress']) && !empty($addressData)) {
        // Delete User Address
        $address_id = $addressData['id'];

        // Prepare and execute the SQL query to delete the address using conn
        $sql = "DELETE FROM `user_addresses` WHERE `user_id` = ? AND `id` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $address_id);

        if ($stmt->execute()) {
            $message  =  "Address deleted successfully!";
            // Reset $addressData to an empty array as the address is deleted
            $addressData = array();
        } else {
            $message = "Error deleting the address: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Reload the addressData after changes
if (!empty($addressData)) {
    $address_id = $addressData['id'];
    $sql = "SELECT * FROM `user_addresses` WHERE `id` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $address_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the updated address details
        $addressData = $result->fetch_assoc();
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Address Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- Add your custom CSS styles here -->
    <style>
        /* Add your custom styles here */
    </style>
</head>


<body>
    <?php require './components/Navbar.php' ?>

<div class="bg-gray-100 min-h-screen flex items-center justify-center">


    <div class="bg-white rounded-lg p-8 shadow-md max-w-md w-full">
        <h1 class="text-2xl font-bold mb-4">User Address</h1>
        <?php if (empty($addressData)) : ?>
            <!-- Address Form -->
            <form method="POST" action="" class="space-y-4">
                <div>
                    <label for="name" class="block font-semibold">Name:</label>
                    <input type="text" id="name" name="name" required class="w-full border border-gray-300 rounded p-2">
                </div>
                <div>
                    <label for="mobile" class="block font-semibold">Mobile:</label>
                    <input type="text" id="mobile" name="mobile" required class="w-full border border-gray-300 rounded p-2">
                </div>
                <div>
                    <label for="alt_mobile" class="block font-semibold">Alternative Mobile:</label>
                    <input type="text" id="alt_mobile" name="alt_mobile" class="w-full border border-gray-300 rounded p-2">
                </div>
                <div>
                    <label for="district" class="block font-semibold">District:</label>
                    <input type="text" id="district" name="district" required class="w-full border border-gray-300 rounded p-2">
                </div>
                <div>
                    <label for="taluka" class="block font-semibold">Taluka:</label>
                    <input type="text" id="taluka" name="taluka" required class="w-full border border-gray-300 rounded p-2">
                </div>
                <div>
                    <label for="village" class="block font-semibold">Village:</label>
                    <input type="text" id="village" name="village" required class="w-full border border-gray-300 rounded p-2">
                </div>
                <div>
                    <label for="address" class="block font-semibold">Address:</label>
                    <textarea id="address" name="address" rows="4" required class="w-full border border-gray-300 rounded p-2"></textarea>
                </div>
                <div>
                    <label for="pincode" class="block font-semibold">Pincode:</label>
                    <input type="text" id="pincode" name="pincode" required class="w-full border border-gray-300 rounded p-2">
                </div>
                <div class="text-right">
                    <button type="submit" name="saveAddress" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save Address</button>
                </div>
            </form>
        <?php else : ?>
            <!-- Display Address Details -->
            <div class="space-y-2" id="dspaddress">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold">Address Details</h2>
                    <div class="space-x-2">
                        <a href="#" id="editAddress" class="text-blue-500 hover:underline flex-col justify-center items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2 2a2 2 0 012-2h14a2 2 0 012 2V16a2 2 0 01-2 2H4a2 2 0 01-2-2V2zm2-1a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V2a1 1 0 00-1-1H4z"/>
                                <path d="M10 4a1 1 0 110 2h-4a1 1 0 110-2h4zm0 4a1 1 0 1100 2h-4a1 1 0 110-2h4zm0 4a1 1 0 110 2h-4a1 1 0 110-2h4zm0 4a1 1 0 110 2h-4a1 1 0 110-2h4z"/>
                            </svg>
                            <p>Edit/Delete</p>
                        </a>
                    </div>
                </div>
                <p><strong>Name:</strong> <?php echo $addressData['name']; ?></p>
                <p><strong>Mobile:</strong> <?php echo $addressData['mobile']; ?></p>
                <p><strong>Alternative Mobile:</strong> <?php echo $addressData['alt_mobile']; ?></p>
                <p><strong>District:</strong> <?php echo $addressData['district']; ?></p>
                <p><strong>Taluka:</strong> <?php echo $addressData['taluka']; ?></p>
                <p><strong>Village:</strong> <?php echo $addressData['village']; ?></p>
                <p><strong>Address:</strong> <?php echo $addressData['address']; ?></p>
                <p><strong>Pincode:</strong> <?php echo $addressData['pincode']; ?></p>
            </div>
            <form method="POST" action="" class="mt-4 space-y-4 hidden" id="editForm">
                <input type="hidden" name="address_id" value="<?php echo $addressData['id']; ?>">
                <div>
                    <label for="name" class="block font-semibold">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo $addressData['name']; ?>" required class="w-full border border-gray-300 rounded p-2">
                </div>
                <div>
                    <label for="mobile" class="block font-semibold">Mobile:</label>
                    <input type="text" id="mobile" name="mobile" value="<?php echo $addressData['mobile']; ?>" required class="w-full border border-gray-300 rounded p-2">
                </div>
                <div>
                    <label for="alt_mobile" class="block font-semibold">Alternative Mobile:</label>
                    <input type="text" id="alt_mobile" name="alt_mobile" value="<?php echo $addressData['alt_mobile']; ?>" class="w-full border border-gray-300 rounded p-2">
                </div>
                <div>
                    <label for="district" class="block font-semibold">District:</label>
                    <input type="text" id="district" name="district" value="<?php echo $addressData['district']; ?>" required class="w-full border border-gray-300 rounded p-2">
                </div>
                <div>
                    <label for="taluka" class="block font-semibold">Taluka:</label>
                    <input type="text" id="taluka" name="taluka" value="<?php echo $addressData['taluka']; ?>" required class="w-full border border-gray-300 rounded p-2">
                </div>
                <div>
                    <label for="village" class="block font-semibold">Village:</label>
                    <input type="text" id="village" name="village" value="<?php echo $addressData['village']; ?>" required class="w-full border border-gray-300 rounded p-2">
                </div>
                <div>
                    <label for="address" class="block font-semibold">Address:</label>
                    <textarea id="address" name="address" rows="4" required class="w-full border border-gray-300 rounded p-2"><?php echo $addressData['address']; ?></textarea>
                </div>
                <div>
                    <label for="pincode" class="block font-semibold">Pincode:</label>
                    <input type="text" id="pincode" name="pincode" value="<?php echo $addressData['pincode']; ?>" required class="w-full border border-gray-300 rounded p-2">
                </div>
                <div class="text-right">
                    <button type="submit" name="editAddress" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Save Changes</button>
                    <button type="submit" name="deleteAddress" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Delete Address</button>
                </div>
            </form>
        <?php endif; ?>

    </div>
    </div>
    <?php require './components/Footer.php' ?>
    <script>
        const editForm = document.getElementById('editForm');
        const editAddressLink = document.getElementById('editAddress');
        const dspaddress = document.getElementById('dspaddress');

        editAddressLink.addEventListener('click', function (e) {
            e.preventDefault();
            editForm.classList.toggle('hidden');
            dspaddress.classList.toggle('hidden');
        });

        // Add SweetAlert for success messages
        <?php if ($message) : ?>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '<?php echo $message; ?>',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>
    </script>
    
</body> 
</html>
