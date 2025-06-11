<?php
/**
 * Model: OrderItem
 * Xử lý các thao tác với bảng order_items
 */
class OrderItem extends BaseModel
{
    protected $table = 'order_items';

    /**
     * Tạo nhiều order item cho 1 đơn hàng
     *
     * @param int   $orderId ID đơn hàng
     * @param array $items   Mảng item với keys:
     *                      - product_variant_id
     *                      - quantity
     *                      - price
     */
    public function createItems(int $orderId, array $items): void
    {
        $sql = "INSERT INTO {$this->table} (order_id, product_variant_id, quantity, price)
                VALUES (:order_id, :pv_id, :qty, :price)";
        $stmt = $this->pdo->prepare($sql);

        foreach ($items as $item) {
            $stmt->execute([
                ':order_id' => $orderId,
                ':pv_id'    => $item['product_variant_id'],
                ':qty'      => $item['quantity'],
                ':price'    => $item['price'],
            ]);
        }
    }

    /**
     * Lấy danh sách order items kèm thông tin sản phẩm
     *
     * @param int $orderId
     * @return array
     */
    public function getItemsByOrder(int $orderId): array
    {
        return $this->getByOrderIdWithProductDetail($orderId);
    }

    /**
     * Lấy order items theo order kèm detail sản phẩm và variant
     *
     * @param int $orderId
     * @return array
     */
    public function getByOrderIdWithProductDetail(int $orderId): array
    {
        $sql = "SELECT oi.*, pv.product_id, p.name AS product_name, pv.size, pv.color
                FROM {$this->table} oi
                JOIN product_variants pv ON oi.product_variant_id = pv.id
                JOIN products p ON pv.product_id = p.id
                WHERE oi.order_id = :order_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['order_id' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
