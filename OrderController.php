<?php

class OrderController
{
    private $orderModel;

    public function __construct($orderModel)
    {
        $this->orderModel = $orderModel;
    }

    public function placeOrder($customerId, $cartItems)
    {
        // Calculate total order amount from cart items
        $totalOrderAmount = 0;
        foreach ($cartItems as $item) {
            $totalOrderAmount += $item['Gia'] * $item['SoLuong'];
        }

        // Create a new order
        $orderId = $this->orderModel->createOrder($customerId, $totalOrderAmount);

        if ($orderId) {
            // Add order details (cart items) to the order
            foreach ($cartItems as $item) {
                $this->orderModel->addOrderDetail($orderId, $item['IDMonAn'], $item['SoLuong']);
            }

            // Clear the cart after placing the order
            unset($_SESSION['cart']);

            // Redirect to a thank you page or order summary page
            header("Location: ../View/profile.php?order_id=$orderId");
            exit();
        } else {
            echo "Failed to place the order. Please try again.";
        }
    }
}
?>
