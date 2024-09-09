<?php 

require_once './connectDB.php';

$limit = 3; // Số lượng bản ghi trên mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Lấy số trang hiện tại
$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM products LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);

$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();

$results = $stmt->fetchAll();

echo "<pre>";
foreach ($results as $row) {
    echo $row['name'] . " - " . $row['price'] . PHP_EOL;
}

