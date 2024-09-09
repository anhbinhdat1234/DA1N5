<?php
function divide($numerator, $denominator)
{
    if ($denominator == 0) {
        throw new Exception("Lỗi: không thể chia cho 0");
    }

    return $numerator / $denominator;
}

echo '<pre>';

try {
    echo divide(10, 2) . PHP_EOL; // Kết quả: 5
    echo divide(10, 0) . PHP_EOL; // Gây ra ngoại lệ
} catch (Exception $e) {
    echo 'Bắt lỗi: ' . $e->getMessage();
}
