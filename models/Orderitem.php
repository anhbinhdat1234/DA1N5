<?php
// models/OrderItem.php

class OrderItem extends BaseModel
{
    protected $table = 'order_items';

    /**
     * Chèn nhiều dòng chi tiết đơn hàng
     *
     * @param int   $orderId
     * @param array $items  Mỗi phần tử gồm [
     *                      'product_variant_id'=>int,
     *                      'quantity'=>int,
     *                      'price'=>float
     *                    ]
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
        foreach ($items as $it) {
            $stmt->execute([
                ':oid'   => $orderId,
                ':vid'   => $it['product_variant_id'],
                ':qty'   => $it['quantity'],
                ':price' => $it['price'],
            ]);
        }
    }

    /**
     * Lấy chi tiết đơn hàng theo order_id
     */
    public function getItemsByOrder(int $orderId): array
    {
        $sql = "
            SELECT oi.*, p.name AS product_name, pv.color, pv.size
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
