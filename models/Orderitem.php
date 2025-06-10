<?php
class OrderItem extends BaseModel
{
    protected $table = 'order_items';

    public function getByOrderIdWithProductDetail($orderId)
    {
        $sql = "SELECT oi.*, pv.product_id, p.name as product_name, pv.size, pv.color
FROM order_items oi
JOIN product_variants pv ON oi.product_variant_id = pv.id
JOIN products p ON pv.product_id = p.id
WHERE oi.order_id = :order_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['order_id' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
