<?php
// Include the database connection file
include_once 'dbconnection.php';

// Get the user ID from the cookie (you should set this cookie when the user logs in)
$user_id = $_COOKIE['user_id'];

// Initialize variables for form fields and error messages
$id = "";
$name = "";
$mobile = "";
$email = "";
$alt_mobile = "";
$district = "";
$taluka = "";
$village = "";
$address = "";
$pincode = "";

$errors = [];

// Check if an operation is requested (create, update, or delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        // Handle create operation here
        // Validate and sanitize input data
        $name = $_POST['name'];
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];
        $alt_mobile = $_POST['alt_mobile'];
        $district = $_POST['district'];
        $taluka = $_POST['taluka'];
        $village = $_POST['village'];
        $address = $_POST['address'];
        $pincode = $_POST['pincode'];

        // Validate inputs
        if (empty($name)) {
            $errors[] = "Name is required.";
        }
        // Add more validation rules as needed

        if (empty($errors)) {
            // Insert the new address into the database
            $sql = "INSERT INTO `user_addresses` (`user_id`, `name`, `mobile`, `email`, `alt_mobile`, `district`, `taluka`, `village`, `address`, `pincode`)
                    VALUES ('$user_id', '$name', '$mobile', '$email', '$alt_mobile', '$district', '$taluka', '$village', '$address', '$pincode')";
            
            if ($conn->query($sql) === TRUE) {
                echo "Address added successfully.";
            } else {
                echo "Error adding address: " . $conn->error;
            }
        }
    } elseif (isset($_POST['update'])) {
        // Handle update operation here
        $id = $_POST['id'];
        // Validate and sanitize input data
        $name = $_POST['name'];
        $mobile = $_POST['mobile'];
        $email = $_POST['email'];
        $alt_mobile = $_POST['alt_mobile'];
        $district = $_POST['district'];
        $taluka = $_POST['taluka'];
        $village = $_POST['village'];
        $address = $_POST['address'];
        $pincode = $_POST['pincode'];

        // Validate inputs
        if (empty($name)) {
            $errors[] = "Name is required.";
        }
        // Add more validation rules as needed

        if (empty($errors)) {
            // Update the address in the database
            $sql = "UPDATE `user_addresses` SET `name`='$name', `mobile`='$mobile', `email`='$email', `alt_mobile`='$alt_mobile', `district`='$district', `taluka`='$taluka', `village`='$village', `address`='$address', `pincode`='$pincode' WHERE `id`='$id'";
            
            if ($conn->query($sql) === TRUE) {
                echo "Address updated successfully.";
            } else {
                echo "Error updating address: " . $conn->error;
            }
        }
    } elseif (isset($_POST['delete'])) {
        // Handle delete operation here
        $delete_id = $_POST['delete'];

        // Implement the delete operation
        $sql = "DELETE FROM `user_addresses` WHERE `id` = $delete_id";
        if ($conn->query($sql) === TRUE) {
            echo "Address deleted successfully.";
        } else {
            echo "Error deleting address: " . $conn->error;
        }
    }
}

// Fetch user addresses from the database
$sql = "SELECT `id`, `user_id`, `name`, `mobile`, `email`, `alt_mobile`, `district`, `taluka`, `village`, `address`, `pincode` FROM `user_addresses` WHERE `user_id` = $user_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Address CRUD App</title>
    <!-- Include Tailwind CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-semibold mb-4">User Addresses</h1>

        <!-- Display addresses or form based on conditions -->
        <?php if (!isset($_POST['edit_id'])): ?>
            <!-- Display addresses -->
            <div class="mb-4">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='bg-white rounded-lg p-4 mb-4'>";
                        echo "<h2 class='text-lg font-semibold mb-2'>{$row['name']}</h2>";
                        echo "<div class='mb-2'><strong>Mobile:</strong> {$row['mobile']}</div>";
                        echo "<div class='mb-2'><strong>Email:</strong> {$row['email']}</div>";
                        echo "<div class='mb-2'><strong>Alternate Mobile:</strong> {$row['alt_mobile']}</div>";
                        echo "<div class='mb-2'><strong>District:</strong> {$row['district']}</div>";
                        echo "<div class='mb-2'><strong>Taluka:</strong> {$row['taluka']}</div>";
                        echo "<div class='mb-2'><strong>Village:</strong> {$row['village']}</div>";
                        echo "<div class='mb-2'><strong>Address:</strong> {$row['address']}</div>";
                        echo "<div><strong>Pincode:</strong> {$row['pincode']}</div>";
                        echo "<div class='mt-4'>";
                        echo "<form method='POST'>";
                        echo "<input type='hidden' name='edit_id' value='{$row['id']}'>";
                        echo "<button type='submit' class='bg-blue-500 text-white px-2 py-1' name='edit'>Edit</button>";
                        echo "<button type='submit' class='bg-red-500 text-white px-2 py-1' name='delete' value='{$row['id']}'>Delete</button>";
                        echo "</form>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "No addresses found.";
                }
                ?>
            </div>
        <?php endif; ?>

        <!-- Edit address form -->
        <?php if (isset($_POST['edit_id'])): ?>
            <h2 class="text-xl font-semibold mb-2">Edit Address</h2>
            <form method="POST" action="index.php">
                <?php
                $edit_id = $_POST['edit_id'];
                // Fetch the address details from the database based on edit_id
                $sql = "SELECT * FROM `user_addresses` WHERE `id` = $edit_id";
                $edit_result = $conn->query($sql);
                if ($edit_result->num_rows > 0) {
                    $edit_row = $edit_result->fetch_assoc();
                    // Populate form fields with values from the database
                    $name = $edit_row['name'];
                    $mobile = $edit_row['mobile'];
                    $email = $edit_row['email'];
                    $alt_mobile = $edit_row['alt_mobile'];
                    $district = $edit_row['district'];
                    $taluka = $edit_row['taluka'];
                    $village = $edit_row['village'];
                    $address = $edit_row['address'];
                    $pincode = $edit_row['pincode'];
                    $id = $edit_row['id'];
                }
                ?>

                <!-- Rest of the form fields here with values populated from the database -->
                <div class="mb-2">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" value="<?php echo $name; ?>" required>
                </div>

                <div class="mb-2">
                    <label for="mobile">Mobile:</label>
                    <input type="text" name="mobile" id="mobile" value="<?php echo $mobile; ?>" required>
                </div>

                <div class="mb-2">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" value="<?php echo $email; ?>" required>
                </div>

                <div class="mb-2">
                    <label for="alt_mobile">Alternate Mobile:</label>
                    <input type="text" name="alt_mobile" id="alt_mobile" value="<?php echo $alt_mobile; ?>">
                </div>

                <div class="mb-2">
                    <label for="district">District:</label>
                    <input type="text" name="district" id="district" value="<?php echo $district; ?>" required>
                </div>

                <div class="mb-2">
                    <label for="taluka">Taluka:</label>
                    <input type="text" name="taluka" id="taluka" value="<?php echo $taluka; ?>" required>
                </div>

                <div class="mb-2">
                    <label for="village">Village:</label>
                    <input type="text" name="village" id="village" value="<?php echo $village; ?>" required>
                </div>

                <div class="mb-2">
                    <label for="address">Address:</label>
                    <textarea name="address" id="address" rows="3" required><?php echo $address; ?></textarea>
                </div>

                <div class="mb-2">
                    <label for="pincode">Pincode:</label>
                    <input type="text" name="pincode" id="pincode" value="<?php echo $pincode; ?>" required>
                </div>

                <input type="hidden" name="id" value="<?php echo $id; ?>">
                
                <div class="mb-4">
                    <button type="submit" class="bg-blue-500 text-white px-2 py-1" name="update">Update</button>
                </div>
            </form>
        <?php endif; ?>

        <!-- Address form for creating new address -->
        <?php if (!isset($_POST['edit_id']) && $result->num_rows == 0): ?>
            <div class="mt-4">
                <h2 class="text-xl font-semibold mb-2">Add New Address</h2>
                <form method="POST" action="index.php">
                    <!-- Rest of the form fields for creating a new address -->
                    <div class="mb-2">
                        <label for="name">Name:</label>
                        <input type="text" name="name" id="name" required>
                    </div>

                    <div class="mb-2">
                        <label for="mobile">Mobile:</label>
                        <input type="text" name="mobile" id="mobile" required>
                    </div>

                    <div class="mb-2">
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" required>
                    </div>

                    <div class="mb-2">
                        <label for="alt_mobile">Alternate Mobile:</label>
                        <input type="text" name="alt_mobile" id="alt_mobile">
                    </div>

                    <div class="mb-2">
                        <label for="district">District:</label>
                        <input type="text" name="district" id="district" required>
                    </div>

                    <div class="mb-2">
                        <label for="taluka">Taluka:</label>
                        <input type="text" name="taluka" id="taluka" required>
                    </div>

                    <div class="mb-2">
                        <label for="village">Village:</label>
                        <input type="text" name="village" id="village" required>
                    </div>

                    <div class="mb-2">
                        <label for="address">Address:</label>
                        <textarea name="address" id="address" rows="3" required></textarea>
                    </div>

                    <div class="mb-2">
                        <label for="pincode">Pincode:</label>
                        <input type="text" name="pincode" id="pincode" required>
                    </div>

                    <div class="mb-4">
                        <button type="submit" class="bg-green-500 text-white px-2 py-1" name="create">Create</button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
        
        <!-- Display error messages -->
        <?php if (!empty($errors)): ?>
            <div class="mt-4">
                <ul class="text-red-500">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
