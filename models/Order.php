<?php
// models/Order.php

require_once __DIR__ . '/BaseModel.php';

class Order extends BaseModel
{
    protected $table = 'orders';

    /**
     * Tạo mới order, trả về order_id
     *
     * @param int         $userId         ID người dùng
     * @param float       $totalAmount    Tổng tiền sau giảm
     * @param string|null $couponCode     Mã giảm giá (nếu có)
     * @param int         $discountAmount Số tiền đã giảm
     * @return int                         ID của order mới
     */
    public function createOrder(int $userId, float $totalAmount, ?string $couponCode = null, int $discountAmount = 0): int
    {
        $sql = "
            INSERT INTO {$this->table}
                (user_id, total, coupon_code, discount_amount, created_at)
            VALUES
                (:uid, :total, :code, :discount, NOW())
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':uid'      => $userId,
            ':total'    => $totalAmount,
            ':code'     => $couponCode,
            ':discount' => $discountAmount,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Lấy thông tin một order theo ID
     *
     * @param int $orderId
     * @return array|null
     */
    public function findOrderById(int $orderId): ?array
    {
        $sql = "
            SELECT o.*, s.address AS shipping_address, s.phone AS shipping_phone, s.note AS shipping_note
            FROM {$this->table} o
            LEFT JOIN shippings s ON s.order_id = o.id
            WHERE o.id = :oid
            LIMIT 1
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':oid' => $orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Lấy danh sách orders của một user, kèm địa chỉ shipping
     *
     * @param int $userId
     * @return array
     */
    public function getOrdersByUser(int $userId): array
    {
        $sql = "
            SELECT 
                o.id,
                o.created_at,
                o.status,
                o.total,
                o.coupon_code,
                o.discount_amount,
                s.address AS shipping_address,
                s.phone   AS shipping_phone,
                s.note    AS shipping_note
            FROM {$this->table} o
            LEFT JOIN shippings s ON s.order_id = o.id
            WHERE o.user_id = :uid
            ORDER BY o.created_at DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cập nhật địa chỉ giao hàng trong bảng shippings
     *
     * @param int    $orderId
     * @param string $newAddress
     * @return bool
     */
    public function updateAddress(int $orderId, string $newAddress): bool
    {
        $sql = "
            UPDATE shippings
               SET address = :addr
             WHERE order_id = :oid
        ";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':addr' => $newAddress,
            ':oid'  => $orderId,
        ]);
    }

    /**
     * Hủy đơn (thay đổi status thành 'cancelled')
     *
     * @param int $orderId
     * @return bool
     */
    public function cancel(int $orderId): bool
    {
        $sql = "
            UPDATE {$this->table}
               SET status = 'cancelled'
             WHERE id = :oid
        ";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':oid' => $orderId]);
    }

    /**
     * Giảm tồn kho theo các item trong cart
     *
     * @param array $cartItems Mảng các phần tử có keys:
     *                         - product_variant_id
     *                         - quantity
     */
    public function reduceStock(array $cartItems): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE product_variants
               SET stock = stock - :qty
             WHERE id = :vid
        ");
        foreach ($cartItems as $it) {
            $stmt->execute([
                ':qty' => $it['quantity'],
                ':vid' => $it['product_variant_id'],
            ]);
        }
    }
}
