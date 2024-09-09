<?php

require_once './connectDB.php';

$productId = 1; // ID của sản phẩm cần cập nhật
$newName = "LAPTOP OK";
$newPrice = 149.99;

$sql = "UPDATE products SET name = :name, price = :price WHERE id = :id";

$stmt = $pdo->prepare($sql);

// $stmt->bindParam(':name', $newName, PDO::PARAM_STR);
// $stmt->bindParam(':price', $newPrice, PDO::PARAM_STR);
// $stmt->bindParam(':id', $productId, PDO::PARAM_INT);

$stmt->execute([
    'name' => $newName,
    'price' => $newPrice,
    'id' => $productId
]);

echo "Product updated successfully.";

