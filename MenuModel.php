
<?php

class MenuModel {
    private $database;

    public function __construct($database) {
        $this->database = $database;
    }

    public function getDistinctFoodCategories() {
        return $this->database->getDistinctFoodCategories();
    }
    public function getFoodItemsByPriceRange($minPrice, $maxPrice) {
        return $this->database->getFoodItemsByPriceRange($minPrice, $maxPrice);
    }
}
