<?php
class OrderItem extends BaseModel
{
    protected $table = 'order_items';

    /**
     * Tạo các order_items khi checkout
     *
     * @param int   $orderId
     * @param array $items   Mảng mỗi phần tử có keys:
     *                       - product_variant_id
     *                       - quantity
     *                       - price
     */
    public function createItems(int $orderId, array $items): void
    {
        $sql = "INSERT INTO {$this->table}
                (order_id, product_variant_id, quantity, price)
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
     * Lấy tất cả items theo đơn hàng, kèm thông tin product/variant
     *
     * @param int $orderId
     * @return array
     */
    public function getItemsByOrder(int $orderId): array
    {
        return $this->getByOrderIdWithProductDetail($orderId);
    }

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

    /**
     * Cập nhật số lượng 1 order_item
     *
     * @param int $itemId
     * @param int $quantity
     * @return bool
     */
    public function updateQuantity(int $itemId, int $quantity): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE {$this->table}
             SET quantity = :qty
             WHERE id = :id"
        );
        return $stmt->execute([
            ':qty' => $quantity,
            ':id'  => $itemId,
        ]);
    }

    /**
     * Xóa 1 order_item
     *
     * @param int $itemId
     * @return bool
     */
    public function deleteItem(int $itemId): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $itemId]);
    }
}
