<?php
session_start();

// Include necessary files
include '../Model/Database.php';
include '../Model/OrderModel.php'; // Assuming you have an OrderModel class
include 'OrderController.php';

// Instantiate objects
$database = new Database();
$orderModel = new OrderModel($database);
$orderController = new OrderController($orderModel);

// Check if the user is logged in (you might want to add additional checks)
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Check if the cart is not empty
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $totalPrice = calculateTotalPrice($_SESSION['cart']); // Implement this function

        // Place the order
        $orderController->placeOrder($userId, $totalPrice);
    } else {
        // Redirect to the cart page with a message
        header("Location: cart_view.php?message=Your cart is empty.");
        exit();
    }
} else {
    // Redirect to the login page or handle unauthorized access
    header("Location: login.php");
    exit();
}

// Function to calculate total price from the cart items
function calculateTotalPrice($cart)
{
    $totalPrice = 0;

    foreach ($cart as $cartItem) {
        $totalPrice += $cartItem['Gia'] * $cartItem['SoLuong'];
    }

    return $totalPrice;
}
?>
