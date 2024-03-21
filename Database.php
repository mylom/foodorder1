    <?php

    class Database
    {
        private $host = "localhost";
        private $username = "root";
        private $password = "";
        private $dbname = "foodorder";
        public $conn;

        public function __construct()
        {
            try {
                $dsn = "mysql:host={$this->host};dbname={$this->dbname}";
                $options = array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                );

                $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }

        public function getConnection()
        {
            return $this->conn;
        }

        public function executeQuery($query, $params = array())
        {
            try {
                $stmt = $this->conn->prepare($query);
        
                // Bind parameters if there are any
                foreach ($params as $key => &$value) {
                    $stmt->bindParam($key, $value);
                }
        
                $stmt->execute();
                return $stmt->fetchAll();
            } catch (PDOException $e) {
                echo "Query execution failed: " . $e->getMessage();
            }
        }
        public function searchFoodItems($keyword) {
            $query = "SELECT idmonan, tenmonan, gia, hinhanh FROM monan WHERE tenmonan LIKE :keyword";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }  

        // Inside the Database class in Database.php
        public function getDistinctFoodCategories() {
            $query = "SELECT DISTINCT LoaiMonAn FROM monan";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        }

        
        public function getFoodItemsByPriceRange($minPrice, $maxPrice) {
            $query = "SELECT * FROM monan WHERE gia BETWEEN :minPrice AND :maxPrice";
            $params = [':minPrice' => $minPrice, ':maxPrice' => $maxPrice];
    
            // Execute the query and return the result
            return $this->executeQuery($query, $params);
        }
        
        
    }

    ?>
