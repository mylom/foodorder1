<?php 
require_once('../Model/database.php');

// Tạo đối tượng Database
$database = new Database();

// Kết nối đến cơ sở dữ liệu
$conn = $database->getConnection();

// Câu truy vấn
$query = "
   SELECT
       ma.LoaiMonAn,
       SUM(ctdh.SoLuong) AS TongSoLuongMua
   FROM
       MonAn ma
   JOIN
       ChiTietDonHang ctdh ON ma.IDMonAn = ctdh.IDMonAn
   GROUP BY
       ma.LoaiMonAn
   ORDER BY
       TongSoLuongMua DESC
   LIMIT 6;
";

// Thực hiện câu truy vấn
$result = $database->executeQuery($query);

$queryTopSellingItems = "
SELECT
    MA.IDMONAN,
    MA.TenMonAn,
    MA.Gia,
    MA.HinhAnh,
    SUM(CTDH.SoLuong) AS SoLuongBanDuoc
FROM
    MonAn MA
JOIN
    ChiTietDonHang CTDH ON MA.IDMonAn = CTDH.IDMonAn
GROUP BY
    MA.IDMonAn
ORDER BY
    SoLuongBanDuoc DESC
LIMIT 6;
";
$resultTopSellingItems = $database->executeQuery($queryTopSellingItems);



?>

