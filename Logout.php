<?php
session_start();

// Hủy toàn bộ session
session_destroy();

// Chuyển hướng về trang chủ hoặc trang đăng nhập
header("Location: ../view/mainpage.php"); // Thay đổi đường dẫn tùy thuộc vào trang chủ của bạn
exit();
?>
