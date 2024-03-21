<?php

include '../Model/Database.php';
include '../Model/MenuModel.php';

$database = new Database();
$conn = $database->getConnection();
$menuModel = new MenuModel($database);
$foodCategories = $menuModel->getDistinctFoodCategories();

$selectedCategories = isset($_GET['categories']) ? $_GET['categories'] : [];

$results_per_page = 6;

if (!isset($_GET['page'])) {
    $page = 1;
} else {
    // Cast to integer to ensure it's numeric
    $page = (int)$_GET['page'];
}

$start_from = (int)($page - 1) * $results_per_page;

if (!empty($_GET['keyword']) && !empty($selectedCategories)) {
    // Handle both keyword search and checkbox filter
    $keyword = $_GET['keyword'];
    
    $placeholders = rtrim(str_repeat(':category' . ($index + 1) . ',', count($selectedCategories)), ',');
    $query = "SELECT idmonan, tenmonan, gia, hinhanh FROM monan WHERE LoaiMonAn IN ($placeholders) LIMIT $start_from, $results_per_page";
    $stmt = $database->conn->prepare($query);

    foreach ($selectedCategories as $index => $category) {
        $stmt->bindValue(':category' . ($index + 1), $category, PDO::PARAM_STR);
    }

    $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
    
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate total pages for combined filter results
    $sql_count = "SELECT COUNT(idmonan) AS total FROM monan WHERE LoaiMonAn IN ($placeholders) ";
} elseif (!empty($_GET['keyword'])) {
    // Handle only keyword search
    $keyword = $_GET['keyword'];

    $query = "SELECT idmonan, tenmonan, gia, hinhanh FROM monan WHERE tenmonan LIKE :keyword LIMIT $start_from, $results_per_page";
    $stmt = $database->conn->prepare($query);
    $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate total pages for search results
    $sql_count = "SELECT COUNT(idmonan) AS total FROM monan WHERE tenmonan LIKE :keyword";
} elseif (!empty($selectedCategories)) {
    // Handle only checkbox filter
    $placeholders = rtrim(str_repeat('?,', count($selectedCategories)), ',');
    $query = "SELECT idmonan, tenmonan, gia, hinhanh FROM monan WHERE LoaiMonAn IN ($placeholders) LIMIT $start_from, $results_per_page";
    $stmt = $database->conn->prepare($query);

    foreach ($selectedCategories as $index => $category) {
        $stmt->bindValue($index + 1, $category, PDO::PARAM_STR);
    }

    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate total pages for category filter results
    $sql_count = "SELECT COUNT(idmonan) AS total FROM monan WHERE LoaiMonAn IN ($placeholders)";
} else {
    // Handle regular pagination
    $query = "SELECT idmonan, tenmonan, gia, hinhanh FROM monan LIMIT $start_from, $results_per_page";
    $rows = $database->executeQuery($query);

    // Calculate total pages for all results
    $sql_count = "SELECT COUNT(idmonan) AS total FROM monan";
}

$stmt_count = $database->conn->prepare($sql_count);
if (!empty($selectedCategories)) {
    $index = 1;

    foreach ($selectedCategories as $category) {
        $stmt_count->bindValue($index++, $category, PDO::PARAM_STR);
    }
} elseif (isset($keyword)) {
    $stmt_count->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
}

$stmt_count->execute();
$row_count = $stmt_count->fetch(PDO::FETCH_ASSOC);
$total_pages = ceil($row_count['total'] / $results_per_page);

if ($rows) {
    $count = 0;
    foreach ($rows as $row) {
        if ($count % 3 == 0) {
            echo '<div class="row">';
        }

        echo '<style>';
        echo '.card a {';
        echo '    text-decoration: none;';
        echo '}';
        echo '</style>';
        
        echo '<div class="col-4 col-md-4 mb-4">';
        echo '<div class="card">';
        echo '<a href="../View/product_details.php?id=' . $row['idmonan'] . '">';
        
        echo '<img src="../View/Dish/' . $row['hinhanh'] . '.png" class="card-img-top" alt="' . $row['tenmonan'] . '">';
        
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . $row['tenmonan'] . '</h5>';
        echo '<p class="card-text">VND' . $row['gia'] . '</p>';

        
        echo '</div>';
        echo '</a>'; // Close the anchor tag
        echo '</div>';
        echo '</div>';
        
        if ($count % 3 == 2 || $count == count($rows) - 1) {
            echo '</div>';
        }

        $count++;
    }
} else {
    echo "No results found";
}


echo '<div class="pagination">';
for ($i = 1; $i <= $total_pages; $i++) {
    $paginationUrl = "?page=$i";

    if (!empty($_GET['keyword'])) {
        $paginationUrl .= '&keyword=' . $_GET['keyword'];
    } elseif (!empty($selectedCategories)) {
        // Include selected categories in pagination links
        $categoryParams = implode('&categories[]=', $selectedCategories);
        $paginationUrl .= '&categories[]=' . $categoryParams;
    } 

    echo '<a href="' . $paginationUrl . '">' . $i . '</a>';
}

echo '</div>';
?>
