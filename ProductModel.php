
<?php

class ProductModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getProductById($productId)
    {
        $query = "SELECT IDMonAn, TenMonAn, MoTa, Gia, LoaiMonAn, HinhAnh FROM monan WHERE IDMonAn = :productId";
        $params = [':productId' => $productId];

        return $this->database->executeQuery($query, $params);
    }

    public function getSimilarProducts($productId)
    {
        $query = "SELECT IDMonAn, TenMonAn, Gia, HinhAnh FROM monan WHERE IDMonAn != :productId ORDER BY RAND() LIMIT 3";

        $params = [':productId' => $productId];

        return $this->database->executeQuery($query, $params);
    }


    public function getProductDetailsById($productId)
    {
        $query = "SELECT IDMonAn, TenMonAn, MoTa, Gia, LoaiMonAn, HinhAnh FROM monan WHERE IDMonAn = :productId";
        $params = [':productId' => $productId];

        return $this->database->executeQuery($query, $params);
    }
}
