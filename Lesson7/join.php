<?php

require_once './connectDB.php';

$customerId = 1; // ID của khách hàng mà bạn muốn lấy thông tin đơn hàng

$sql = "
    SELECT orders.id, orders.code, customers.name AS customer_name 
    FROM orders 
    JOIN customers ON orders.customer_id = customers.id 
    WHERE customers.id = :customer_id
";

$stmt = $pdo->prepare($sql);

$stmt->bindParam(':customer_id', $customerId, PDO::PARAM_INT);

$stmt->execute();

$results = $stmt->fetchAll();

echo "<pre>";

foreach ($results as $row) {
    echo "Order ID: " . $row['id'] 
        . ", Order Code: " . $row['code'] 
        . ", Customer: " . $row['customer_name'] . PHP_EOL;
}

