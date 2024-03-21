<?php

class OrderModel
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function executeQuery($query, $params = array())
    {
        try {
            $stmt = $this->database->conn->prepare($query);

            // Bind parameters if there are any
            foreach ($params as $key => &$value) {
                $stmt->bindParam($key, $value);
            }

            $stmt->execute();
            return $stmt->rowCount(); // Return the number of affected rows
        } catch (PDOException $e) {
            echo "Query execution failed: " . $e->getMessage();
        }
    }

    public function executeNonQuery($sql, $params = array())
    {
        try {
            $stmt = $this->database->conn->prepare($sql);

            // Bind parameters if there are any
            foreach ($params as $key => &$value) {
                $stmt->bindParam($key, $value);
            }

            $stmt->execute();
            return $stmt->rowCount();  // Return the number of affected rows
        } catch (PDOException $e) {
            echo "Query execution failed: " . $e->getMessage();
        }
    }

    public function createOrder($username, $totalPrice)
    {
        $currentDateTime = date('Y-m-d H:i:s'); // Lấy ngày và giờ hiện tại
    
        $sql = "INSERT INTO DonHang (TenTaiKhoan, TongDonHang, TrangThaiDonHang, NgayDatHang) VALUES (:username, :totalPrice, 'pending', :currentDateTime)";
        $params = [':username' => $username, ':totalPrice' => $totalPrice, ':currentDateTime' => $currentDateTime];
    
        $this->executeNonQuery($sql, $params);
    
        // Get the last inserted order ID
        $orderId = $this->database->conn->lastInsertId();
    
        // Perform other actions related to order creation if needed
    
        return $orderId;
    }

    public function addOrderDetail($orderId, $productId, $quantity)
    {
        $query = "INSERT INTO ChiTietDonHang (IDDonHang, IDMonAn, SoLuong) 
                  VALUES (:orderId, :productId, :quantity)";
        $params = [':orderId' => $orderId, ':productId' => $productId, ':quantity' => $quantity];

        $this->executeQuery($query, $params);
    }

    
public function updateOrderStatus($orderId, $newStatus)
{
    $query = "UPDATE DonHang SET TrangThaiDonHang = :newStatus WHERE IDDonHang = :orderId";
    $params = [':newStatus' => $newStatus, ':orderId' => $orderId];
    return $this->executeNonQuery($query, $params);
}
public function getOrderHistory($tenTaiKhoan) {
    $sql = "SELECT * FROM DonHang WHERE TenTaiKhoan = :tenTaiKhoan";
    $params = [':tenTaiKhoan' => $tenTaiKhoan];

    return $this->database->executeQuery($sql, $params);
}
}

?>
