<?php

class TaiKhoanModel {
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
    public function dangKyTaiKhoan($tenTaiKhoan, $matKhau, $tenKhachHang, $sdt, $email, $ngaySinh, $hinhAnh, $thanhPho, $quan, $phuong, $duong) {
        // Thực hiện thêm thông tin vào cơ sở dữ liệu
        $idKhachHang = $this->layIDKhachHangMoi();
        $hashedPassword = password_hash($matKhau, PASSWORD_DEFAULT);
        $queryKhachHang = "INSERT INTO KhachHang (TenKhachHang, SDT, Email, NgaySinh, HinhAnh) 
                           VALUES (?, ?, ?, ?, ?)";
        $stmtKhachHang = $this->conn->prepare($queryKhachHang);
        $stmtKhachHang->bind_param("sssss", $tenKhachHang, $sdt, $email, $ngaySinh, $hinhAnh);

        if (!$stmtKhachHang->execute()) {
            die("Error executing KhachHang insert: " . $stmtKhachHang->error);
        }

        $stmtKhachHang->close();

        // Lấy IDKhachHang vừa thêm vào


        // Thêm thông tin vào bảng DiaChi
        $queryDiaChi = "INSERT INTO DiaChi (IDKhachHang, ThanhPho, Quan, Phuong, Duong) 
                        VALUES (?, ?, ?, ?, ?)";
        $stmtDiaChi = $this->conn->prepare($queryDiaChi);
        $stmtDiaChi->bind_param("issss", $idKhachHang, $thanhPho, $quan, $phuong, $duong);

        if (!$stmtDiaChi->execute()) {
            die("Error executing DiaChi insert: " . $stmtDiaChi->error);
        }

        $stmtDiaChi->close();

        // Thêm thông tin vào bảng TaiKhoan
        $queryTaiKhoan = "INSERT INTO TaiKhoan (TenTaiKhoan, IDKhachHang, MatKhau) 
                          VALUES (?, ?, ?)";
        $stmtTaiKhoan = $this->conn->prepare($queryTaiKhoan);
        $stmtTaiKhoan->bind_param("sis", $tenTaiKhoan, $idKhachHang, $hashedPassword);

        if (!$stmtTaiKhoan->execute()) {
            die("Error executing TaiKhoan insert: " . $stmtTaiKhoan->error);
        }

        $stmtTaiKhoan->close();
    }
    
    
    

    
    public function layIDKhachHangMoi() {
        $query = "SELECT MAX(IDKhachHang) + 1 AS NewID FROM KhachHang";
        $result = $this->conn->query($query);  // Sửa $this->db thành $this->conn
    
        if ($result === false) {
            // Xử lý lỗi
            return 0; // Hoặc một giá trị mặc định nếu không thể lấy được ID mới
        }
    
        $row = $result->fetch_assoc();
        $newID = ($row['NewID'] !== null) ? $row['NewID'] : 1;
    
        return $newID;
    }


    public function kiemTraTenTaiKhoanTonTai($tenTaiKhoan) {
        $query = "SELECT COUNT(*) AS count FROM TaiKhoan WHERE TenTaiKhoan = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $tenTaiKhoan);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $count = $row['count'];
        $stmt->close();

        return ($count > 0);
    }

    public function kiemTraSoDienThoaiTonTai($sdt) {
        $query = "SELECT COUNT(*) AS count FROM KhachHang WHERE SDT = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $sdt);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $count = $row['count'];
        $stmt->close();

        return ($count > 0);
    }

    public function dangNhap($tenTaiKhoan, $matKhau) {
        $hashedPassword = 
        $query = "SELECT TenTaiKhoan, MatKhau FROM TaiKhoan WHERE TenTaiKhoan = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $tenTaiKhoan);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows === 0) {
            // Username doesn't exist
            return false;
        }
    
        $row = $result->fetch_assoc();
        $hashedPassword = $row['MatKhau'];
    
        if (password_verify($matKhau, $hashedPassword)) {
            // Password is correct, return user ID
            return $row['TenTaiKhoan'];
        } else {
            // Password is incorrect
            return false;
        }
    }
    public function getCustomerInfoByTenTaiKhoan($tenTaiKhoan) {
        $query = "SELECT *
        FROM KhachHang kh
        JOIN TaiKhoan tk ON kh.IDKhachHang = tk.IDKhachHang
        JOIN Diachi dc ON kh.IDKhachHang = dc.IDKhachHang
        WHERE tk.TenTaiKhoan = ?;";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $tenTaiKhoan);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows === 0) {
            // No customer found
            return null;
        }
    
        $customerInfo = $result->fetch_assoc();
        $stmt->close();
    
        return $customerInfo;
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
    public function updateCustomerAddress($tenTaiKhoan, $newAddress) {
        // Validate and sanitize input to prevent SQL injection
    
        // Example:
        $updateFields = '';
        foreach ($newAddress as $key => $value) {
            $updateFields .= "$key = '$value', ";
        }
        $updateFields = rtrim($updateFields, ', ');
    
        $sql = "UPDATE DiaChi
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

