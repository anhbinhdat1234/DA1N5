<?php 

require_once './connectDB.php';

$name = "New Product";
$price = 99.99;

$sql = "INSERT INTO products (name, price) VALUES (:name, :price)";

$stmt = $pdo->prepare($sql);

$stmt->bindParam(':name', $name, PDO::PARAM_STR);
$stmt->bindParam(':price', $price, PDO::PARAM_STR);

$stmt->execute();

echo "Product inserted successfully with ID: " . $pdo->lastInsertId();

