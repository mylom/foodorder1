<?php
require_once '../Model/TaiKhoanModel.php';
require_once 'TaiKhoanController.php';

session_start();

// Kết nối đến cơ sở dữ liệu (Chưa triển khai đầy đủ, bạn cần điều chỉnh để kết nối đúng)
$db = new mysqli('localhost', 'root', '', 'foodorder');

// Kiểm tra kết nối
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Khởi tạo model và controller
$model = new TaiKhoanModel($db);
$controller = new TaiKhoanController($model);

// Xử lý đăng ký khi nhấn submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenTaiKhoan = $_POST['tenTaiKhoan'];
    $matKhau = $_POST['matKhau'];
    $tenKhachHang = $_POST['name'];
    $sdt = $_POST['sdt'];
    $thanhPho = $_POST['thanhpho'];
    $quan = $_POST['quan'];
    $phuong = $_POST['phuong'];
    $duong = $_POST['duong'];

    // Check if the username or phone number already exists
    if ($model->kiemTraTenTaiKhoanTonTai($tenTaiKhoan)) {
        $_SESSION['error_message'] = "Tên tài khoản đã tồn tại. Chọn tên khác.";
        header("Location: ../View/sign-up.php");
        exit();
    }

    if ($model->kiemTraSoDienThoaiTonTai($sdt)) {
        $_SESSION['error_message'] = "Số điện thoại đã được đăng ký. Sử dụng số khác.";
        header("Location: ../View/sign-up.php");
        exit();
    }

    // Continue with the registration
    $controller->xuLyDangKy($tenTaiKhoan, $matKhau, $tenKhachHang, $sdt, $thanhPho, $quan, $phuong, $duong);
    header("Location: ../View/mainpage.php");
    exit();
}

// Đóng kết nối
$db->close();

