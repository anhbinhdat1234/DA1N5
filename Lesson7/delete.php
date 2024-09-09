<?php

require_once './connectDB.php';

$productId = 1; // ID của sản phẩm cần xóa

$sql = "DELETE FROM products WHERE id = :id";

$stmt = $pdo->prepare($sql);

$stmt->bindParam(':id', $productId, PDO::PARAM_INT);

$stmt->execute();

echo "Product deleted successfully.";

