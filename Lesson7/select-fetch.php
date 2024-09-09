<?php 

require_once './connectDB.php';

$sql = "SELECT * FROM products WHERE id = :id";

$stmt = $pdo->prepare($sql);

$id = 4;

$stmt->execute(['id' => $id]);

$result = $stmt->fetch();

echo "<pre>";
print_r($result);

