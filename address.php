<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Address CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php include './components/Navbar.php'?>

    <div class="max-w-md mx-auto bg-white rounded p-4 shadow-lg">
        <h1 class="text-2xl font-bold mb-4">Address CRUD</h1>

        <div id="address-form" class="mb-4">
            <label for="name" class="block">Name:</label>
            <input type="text" id="name" class="w-full border p-2 mb-2 rounded">
            <label for="mobile" class="block">Mobile:</label>
            <input type="tel" id="mobile" class="w-full border p-2 mb-2 rounded">
            <label for="email" class="block">Email:</label>
            <input type="email" id="email" class="w-full border p-2 mb-2 rounded">
            <label for="alt_mobile" class="block">Alternate Mobile:</label>
            <input type="tel" id="alt_mobile" class="w-full border p-2 mb-2 rounded">
            <label for="district" class="block">District:</label>
            <input type="text" id="district" class="w-full border p-2 mb-2 rounded">
            <label for "taluka" class="block">Taluka:</label>
            <input type="text" id="taluka" class="w-full border p-2 mb-2 rounded">
            <label for="village" class="block">Village:</label>
            <input type="text" id="village" class="w-full border p-2 mb-2 rounded">
            <label for="address" class="block">Address:</label>
            <input type="text" id="address" class="w-full border p-2 mb-2 rounded">
            <label for="pincode" class="block">PIN Code:</label>
            <input type="text" id="pincode" class="w-full border p-2 mb-4 rounded">
            <button id="save-address" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-700">Save Address</button>
        </div>

        <div id="address-display" class="hidden">
            <h2 class="text-lg font-bold mb-2">Stored Address</h2>
            <p><strong>Name:</strong> <span id="display-name"></span></p>
            <p><strong>Mobile:</strong> <span id="display-mobile"></span></p>
            <p><strong>Email:</strong> <span id="display-email"></span></p>
            <p><strong>Alternate Mobile:</strong> <span id="display-alt_mobile"></span></p>
            <p><strong>District:</strong> <span id="display-district"></span></p>
            <p><strong>Taluka:</strong> <span id="display-taluka"></span></p>
            <p><strong>Village:</strong> <span id="display-village"></span></p>
            <p><strong>Address:</strong> <span id="display-address"></span></p>
            <p><strong>PIN Code:</strong> <span id="display-pincode"></span></p>
            <button id="edit-address" class="bg-yellow-500 text-white p-2 rounded hover:bg-yellow-700">Edit Address</button>
            <button id="delete-address" class="bg-red-500 text-white p-2 rounded hover:bg-red-700">Delete Address</button>
        </div>
    </div>
    <?php include './components/Footer.php'?>

    <script>
        const addressForm = $("#address-form");
        const addressDisplay = $("#address-display");
        const saveButton = $("#save-address");
        const editButton = $("#edit-address");
        const deleteButton = $("#delete-address");

        const nameInput = $("#name");
        const mobileInput = $("#mobile");
        const emailInput = $("#email");
        const altMobileInput = $("#alt_mobile");
        const districtInput = $("#district");
        const talukaInput = $("#taluka");
        const villageInput = $("#village");
        const addressInput = $("#address");
        const pincodeInput = $("#pincode");

        const display = {
            name: $("#display-name"),
            mobile: $("#display-mobile"),
            email: $("#display-email"),
            alt_mobile: $("#display-alt_mobile"),
            district: $("#display-district"),
            taluka: $("#display-taluka"),
            village: $("#display-village"),
            address: $("#display-address"),
            pincode: $("#display-pincode")
        };

        // Check if there is a stored address
        const storedData = localStorage.getItem("addressData");
        if (storedData) {
            const addressData = JSON.parse(storedData);
            // Populate the input fields with the stored data
            nameInput.val(addressData.name);
            mobileInput.val(addressData.mobile);
            emailInput.val(addressData.email);
            altMobileInput.val(addressData.alt_mobile);
            districtInput.val(addressData.district);
            talukaInput.val(addressData.taluka);
            villageInput.val(addressData.village);
            addressInput.val(addressData.address);
            pincodeInput.val(addressData.pincode);
            displayAddressDetails(addressData);
        } else {
            addressForm.show();
            addressDisplay.hide();
        }

        saveButton.click(function() {
            // Implement your validation here
            const name = nameInput.val();
            const mobile = mobileInput.val();
            const email = emailInput.val();
            const altMobile = altMobileInput.val();
            const district = districtInput.val();
            const taluka = talukaInput.val();
            const village = villageInput.val();
            const address = addressInput.val();
            const pincode = pincodeInput.val();

            // Store the address in a single object
            const addressData = {
                name,
                mobile,
                email,
                alt_mobile: altMobile,
                district,
                taluka,
                village,
                address,
                pincode
            };

            // Store the address object in localStorage
            localStorage.setItem("addressData", JSON.stringify(addressData));

            // Display the saved address
            displayAddressDetails(addressData);
        });

        function displayAddressDetails(addressData) {
            display.name.text(addressData.name);
            display.mobile.text(addressData.mobile);
            display.email.text(addressData.email);
            display.alt_mobile.text(addressData.alt_mobile);
            display.district.text(addressData.district);
            display.taluka.text(addressData.taluka);
            display.village.text(addressData.village);
            display.address.text(addressData.address);
            display.pincode.text(addressData.pincode);

            addressForm.hide();
            addressDisplay.show();
        }

        function clearAddress() {
            nameInput.val("");
            mobileInput.val("");
            emailInput.val("");
            altMobileInput.val("");
            districtInput.val("");
            talukaInput.val("");
            villageInput.val("");
            addressInput.val("");
            pincodeInput.val("");

            localStorage.removeItem("addressData");
        }

        editButton.click(function() {
            addressForm.show();
            addressDisplay.hide();
        });

        deleteButton.click(function() {
            clearAddress();
            addressForm.show();
            addressDisplay.hide();
        });
    </script>
</body>
</html>
