<?php
// models/OrderItem.php

class OrderItem extends BaseModel
{
    protected $table = 'order_items';

    /**
     * Chèn nhiều dòng chi tiết đơn hàng
     *
     * @param int   $orderId
     * @param array $items  Mỗi phần tử mảng là [
     *                      'product_variant_id' => int,
     *                      'quantity'           => int,
     *                      'price'              => float
     *                    ]
     * @throws Exception nếu có lỗi
     */
    public function createItems(int $orderId, array $items): void
    {
        $sql = "
            INSERT INTO {$this->table}
                (order_id, product_variant_id, quantity, price)
            VALUES
                (:oid, :vid, :qty, :price)
        ";
        $stmt = $this->pdo->prepare($sql);

        foreach ($items as $item) {
            $stmt->execute([
                ':oid'   => $orderId,
                ':vid'   => $item['product_variant_id'],
                ':qty'   => $item['quantity'],
                ':price' => $item['price']
            ]);
        }
    }

    /**
     * Lấy danh sách chi tiết đơn hàng theo order_id
     *
     * @param int $orderId
     * @return array
     */
    public function getItemsByOrder(int $orderId): array
    {
        $sql = "
            SELECT oi.*, 
                   pv.color, 
                   pv.size, 
                   p.name AS product_name,
                   p.price AS product_price
            FROM {$this->table} oi
            JOIN product_variants pv ON pv.id = oi.product_variant_id
            JOIN products p ON p.id = pv.product_id
            WHERE oi.order_id = :oid
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':oid' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
