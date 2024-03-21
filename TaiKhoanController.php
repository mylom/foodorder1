<?php

class TaiKhoanController {
    private $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function xuLyDangKy($tenTaiKhoan, $matKhau, $tenKhachHang, $sdt, $thanhPho, $quan, $phuong, $duong) {
        // Check if the username or phone number already exists
        if ($this->model->kiemTraTenTaiKhoanTonTai($tenTaiKhoan)) {
            echo "Tên tài khoản đã tồn tại. Chọn tên khác.";
            return;
        }

        if ($this->model->kiemTraSoDienThoaiTonTai($sdt)) {
            echo "Số điện thoại đã được đăng ký. Sử dụng số khác.";
            return;
        }

        // Continue with the registration
        $this->model->dangKyTaiKhoan($tenTaiKhoan, $matKhau, $tenKhachHang, $sdt, '', '', '', $thanhPho, $quan, $phuong, $duong);
    }

    public function xuLyDangNhap($tenTaiKhoan, $matKhau) {
        $userID = $this->model->dangNhap($tenTaiKhoan, $matKhau);

        if ($userID) {
            // Login successful, store user ID in session
            $_SESSION['user_id'] = $userID;
            header("Location: ../View/mainpage.php");
            exit();
        } else {
            // Login failed
            echo "Đăng nhập không thành công. Vui lòng kiểm tra lại thông tin đăng nhập.";
        }
    }
    public function hienThiThongTinKhachHang() {
        // Check if the user is logged in
        if (!isset($_SESSION['user_id'])) {
            echo "Bạn chưa đăng nhập.";
            return;
        }

        // Get user ID from session
        $userID = $_SESSION['user_id'];

        // Create a ProfileModel instance
        $profileModel = new ProfileModel($this->model->conn);

        // Get customer profile information
        $customerProfile = $profileModel->getCustomerProfile($userID);

        // Check if the profile exists
        if ($customerProfile) {
            // Display customer information in profile.php
            include '../View/profile1.php';
        } else {
            echo "Không tìm thấy thông tin khách hàng.";
        }
    }
        public function capNhatThongTinKhachHang($newData) {
            // Check if the user is logged in
            if (!isset($_SESSION['user_id'])) {
                echo "Bạn chưa đăng nhập.";
                return;
            }

            // Get username from session
            $tenTaiKhoan = $_SESSION['user_id'];

            // Update customer information
            $updateResult = $this->model->updateCustomerInfo($tenTaiKhoan, $newData);

            if ($updateResult) {
                echo "Thông tin khách hàng đã được cập nhật thành công.";
            } else {
                echo "Có lỗi xảy ra khi cập nhật thông tin khách hàng.";
            }
        }
    
    
}



?>

