<?php
/**
 * Model: Order
 * Xử lý các thao tác với bảng orders
 */
class Order extends BaseModel
{
    protected $table = 'orders';

    /**
     * Tạo order mới, trả về order_id
     *
     * @param int         $userId        ID người dùng
     * @param float       $finalAmount   Tổng tiền sau giảm giá
     * @param string|null $couponCode    Mã coupon (nếu có)
     * @param float       $discountAmount Số tiền đã giảm
     * @return int
     */
    public function createOrder(int $userId, float $finalAmount, ?string $couponCode = null, float $discountAmount = 0.0): int
    {
        $sql = "
            INSERT INTO {$this->table}
                (user_id, total, coupon_code, discount_amount, created_at)
            VALUES
                (:uid, :total, :coupon, :discount, NOW())
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':uid'      => $userId,
            ':total'    => $finalAmount,
            ':coupon'   => $couponCode,
            ':discount' => $discountAmount,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Giảm tồn kho cho các variant đã đặt
     *
     * @param array $cartItems Mảng item với keys: product_variant_id, quantity
     */
    public function reduceStock(array $cartItems): void
    {
        $sql  = "UPDATE product_variants SET stock = stock - :qty WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        foreach ($cartItems as $item) {
            $stmt->execute([
                ':qty' => $item['quantity'],
                ':id'  => $item['product_variant_id'],
            ]);
        }
    }

    /**
     * Lấy tất cả orders kèm tên user
     *
     * @return array
     */
    public function getAll(): array
    {
        $sql = "SELECT o.*, u.name AS user_name
            FROM {$this->table} o
            LEFT JOIN users u ON o.user_id = u.id
            ORDER BY o.id DESC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tìm order theo ID
     *
     * @param int $id
     * @return array|null
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Xóa order theo ID
     *
     * @param int $id
     * @return bool
     */
    public function deleteById(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Cập nhật trạng thái order
     *
     * @param int    $id
     * @param string $status
     * @return bool
     */
    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET status = :status WHERE id = :id");
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }
}
