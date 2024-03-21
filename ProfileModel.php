
<?php
class ProfileModel {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "foodorder";
    public $conn;

    public function __construct() {
        // Kết nối đến cơ sở dữ liệu
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->dbname);


        // Kiểm tra kết nối
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }


    public function getCustomerProfile($userID) {
        // Modify the SQL query to fetch the required fields
        $sql = "SELECT kh.TenKhachHang, kh.SDT, kh.Email, kh.NgaySinh, dc.ThanhPho, dc.Quan, dc.Phuong, dc.Duong
        FROM KhachHang kh
        JOIN DiaChi dc ON kh.IDKhachHang = dc.IDKhachHang
        JOIN TaiKhoan tk ON kh.IDKhachHang = tk.IDKhachHang
        WHERE tk.TenTaiKhoan = ?";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }


}
?>
