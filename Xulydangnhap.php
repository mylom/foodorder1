<?php
session_start();
require_once '../Model/TaiKhoanModel.php';
require_once 'TaiKhoanController.php';



// Kết nối đến cơ sở dữ liệu (Chưa triển khai đầy đủ, bạn cần điều chỉnh để kết nối đúng)
$db = new mysqli('localhost', 'root', '', 'foodorder');

// Kiểm tra kết nối
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Khởi tạo model và controller
$model = new TaiKhoanModel($db);
$controller = new TaiKhoanController($model);

// Xử lý đăng nhập khi nhấn submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $tenTaiKhoan = $_POST['tenTaiKhoan'];
    $matKhau = $_POST['matKhau'];
    echo 'User ID in session: ' . $_SESSION['user_id'];
    // Process login
    $userID = $model->dangNhap($tenTaiKhoan, $matKhau);

    if ($userID) {
        // Login successful, store user ID in session
        $_SESSION['user_id'] = $userID;
        header("Location: ../View/Mainpage.php");
        $controller->hienThiThongTinKhachHang();
        exit();
    } else {
        // Login failed
        $_SESSION['error_message'] = "Đăng nhập không thành công. Vui lòng kiểm tra lại thông tin đăng nhập.";
        header("Location: ../View/Sign-in.php");
        exit();
    }
}

// Đóng kết nối
$db->close();
?>
