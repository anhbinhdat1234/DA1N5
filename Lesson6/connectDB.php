<?php

$dsn = 'mysql:host=localhost;port=3306;dbname=testdb;charset=utf8';
$username = 'root';
$password = '';

echo '<pre>';

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_PERSISTENT => true,
    ]);

    echo "Kết nối thành công! \n";
    print_r($pdo);

} catch (PDOException $e) {
    // Xử lý lỗi kết nối
    die("Kết nối cơ sở dữ liệu thất bại: {$e->getMessage()}. Vui lòng thử lại sau.");
}

