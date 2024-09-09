<?php 

require_once './connectDB.php';

$sql = "SELECT * FROM products";

$stmt = $pdo->prepare($sql);

$stmt->execute();

$results = $stmt->fetchAll();

echo "<pre>";

foreach ($results as $row) {
    echo $row['name'] . " - " . $row['price'] . PHP_EOL;
}

