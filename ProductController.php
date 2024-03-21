
<?php

class ProductController
{
    private $productModel;

    public function __construct($productModel)
    {
        $this->productModel = $productModel;
    }

    public function getProductDetails($productId)
    {
        $productDetails = $this->productModel->getProductById($productId);
        $similarProducts = $this->productModel->getSimilarProducts($productId); // Add this line

        // Include the product details view file
        include '../View/product_details_view.php';
    }

    public function addToCart($productId)
    {
        // Get product details by ID
        $productDetails = $this->productModel->getProductDetailsById($productId);
    
        // Add the product to the cart
        $cartItem = [
            'IDMonAn' => $productId,
            'TenMonAn' => $productDetails[0]['TenMonAn'],
            'Gia' => $productDetails[0]['Gia'],
            'HinhAnh' => $productDetails[0]['HinhAnh'],
            'SoLuong' => 1, // Set quantity to 1
        ];
    
        // Initialize the cart if it doesn't exist in the session
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    
        // Check if the product is already in the cart
        $productIndex = array_search($productId, array_column($_SESSION['cart'], 'IDMonAn'));
    
        if ($productIndex !== false) {
            // If the product is already in the cart, ensure "SoLuong" is defined before incrementing
            if (isset($_SESSION['cart'][$productIndex]['SoLuong'])) {
                $_SESSION['cart'][$productIndex]['SoLuong'] += 1;
            } else {
                // If "SoLuong" is not defined, set it to 1
                $_SESSION['cart'][$productIndex]['SoLuong'] = 1;
            }
        } else {
          
            $_SESSION['cart'][] = $cartItem;
        }
    
        
    }

    public function updateCartQuantity($productId, $newQuantity)
{
    // Find the product in the cart
    $productIndex = array_search($productId, array_column($_SESSION['cart'], 'IDMonAn'));

    if ($productIndex !== false) {
        // Update the quantity
        $_SESSION['cart'][$productIndex]['SoLuong'] = $newQuantity;
    }
    // Optionally, you can add error handling if the product is not found
}
}
