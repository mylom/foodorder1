<?php

class UserModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function createUser($name, $email, $thanhpho, $quan, $phuong, $duong, $taikhoan, $password)
    {
        try {
            // Start a transaction
            $this->db->conn->beginTransaction();

            // Insert address first
            $query = "INSERT INTO DiaChi (ThanhPho, Quan, Phuong, Duong) VALUES (:thanhpho, :quan, :phuong, :duong)";
            $params = array(
                ':thanhpho' => $thanhpho,
                ':quan' => $quan,
                ':phuong' => $phuong,
                ':duong' => $duong
            );

            $this->db->executeQuery($query, $params);
            $idDiaChi = $this->db->conn->lastInsertId();

            // Insert user
            $query = "INSERT INTO KhachHang (TenKhachHang, Email, IDDiaChi) VALUES (:name, :email, :idDiaChi)";
            $params = array(
                ':name' => $name,
                ':email' => $email,
                ':idDiaChi' => $idDiaChi
            );

            $this->db->executeQuery($query, $params);
            $idKhachHang = $this->db->conn->lastInsertId();

            // Insert account
            $query = "INSERT INTO TaiKhoan (TenTaiKhoan, IDKhachHang, MatKhau) VALUES (:taikhoan, :idKhachHang, :password)";
            $params = array(
                ':taikhoan' => $taikhoan,
                ':idKhachHang' => $idKhachHang,
                ':password' => password_hash($password, PASSWORD_DEFAULT)
            );

            $this->db->executeQuery($query, $params);

            // Commit the transaction
            $this->db->conn->commit();
            return TRUE;
        } catch (PDOException $e) {
            // Rollback the transaction in case of an error
            $this->db->conn->rollBack();
            echo "Error: " . $e->getMessage();
            return false; // Return false on failure
        }
    }
}

?>
