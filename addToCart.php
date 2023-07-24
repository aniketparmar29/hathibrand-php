<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the product data from the JSON request
    $requestData = json_decode(file_get_contents('php://input'), true);

    // Check if all required fields are present in the requestData
    if (isset($requestData['productId'], $requestData['productName'], $requestData['productImage'], $requestData['productPrice'], $requestData['productWeight'])) {
        $productId = $requestData['productId'];
        $productName = $requestData['productName'];
        $productImage = $requestData['productImage'];
        $productPrice = $requestData['productPrice'];
        $productWeight = $requestData['productWeight'];

        // Create or update the cart session variable
        if (isset($_SESSION['cart'])) {
            $cart = $_SESSION['cart'];
        } else {
            $cart = [];
        }

        // Check if the product already exists in the cart
        $existingProductIndex = -1;
        foreach ($cart as $index => $item) {
            if ($item['productId'] === $productId && $item['productWeight'] === $productWeight) {
                $existingProductIndex = $index;
                break;
            }
        }

        if ($existingProductIndex !== -1) {
            // Product already exists in the cart, update the quantity
            $cart[$existingProductIndex]['quantity']++;
        } else {
            // Product doesn't exist in the cart, add it as a new item
            $cartItem = [
                'productId' => $productId,
                'productName' => $productName,
                'productImage' => $productImage,
                'productPrice' => $productPrice,
                'productWeight' => $productWeight,
                'quantity' => 1
            ];
            $cart[] = $cartItem;
        }

        // Save the updated cart data to the session
        $_SESSION['cart'] = $cart;

        // Send a response back to the client
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit();
    } else {
        // Required fields are missing in the request data
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Required fields are missing.']);
        exit();
    }
} else {
    echo "Invalid request method.";
}
?>
