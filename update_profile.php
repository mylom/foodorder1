<?php
session_start();
require_once '../Controller/TaiKhoanController.php';
require_once '../Model/TaiKhoanModel.php';
$model = new TaiKhoanModel(); // Initialize $model here
$controller = new TaiKhoanController($model);
// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../View/Sign-in.php");
    exit();
}
$tenTaiKhoan = $_SESSION['user_id'];
// Get user inputs


$addressData = array(
    'ThanhPho' => $_POST['thanhPho'],
    'Quan' => $_POST['quan'],
    'Phuong' => $_POST['phuong'],
    'Duong' => $_POST['duong'],
);


$newData = array(
    'TenKhachHang' => $_POST['newName'],
    'Email' => $_POST['newEmail'],
    'SDT' => $_POST['newPhoneNumber'],
    'NgaySinh' => $_POST['newBirthdate'],
   

   
);

// Create controller and update customer information
$model->updateCustomerAddress($tenTaiKhoan, $addressData);
$controller->capNhatThongTinKhachHang($newData);
header("Location: ../View/profile.php");

?>
