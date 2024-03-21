
<?php

class UpdateProfileModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function updateCustomerInfo($tenTaiKhoan, $newData) {
        // Implement the logic to update customer information in the KhachHang table
        // based on the TenTaiKhoan in the TaiKhoan table
        // You should validate and sanitize user input before updating the database
        // $newData is an associative array containing the updated fields

        // Example:
        $updateFields = '';
        foreach ($newData as $key => $value) {
            $updateFields .= "$key = '$value', ";
        }
        $updateFields = rtrim($updateFields, ', ');

        $sql = "UPDATE KhachHang
                SET $updateFields
                WHERE IDKhachHang IN (
                    SELECT kh.IDKhachHang
                    FROM KhachHang kh
                    JOIN TaiKhoan tk ON kh.IDKhachHang = tk.IDKhachHang
                    WHERE tk.TenTaiKhoan = '$tenTaiKhoan'
                )";

        $result = $this->conn->query($sql);

        return $result;
    }
}
?>
