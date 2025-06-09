<?php
// app/models/Order.php

require_once __DIR__ . '/BaseModel.php';

class Order extends BaseModel
{
    protected $table = 'orders';

    /**
     * Tạo mới order, trả về order_id
     *
     * @param int         $userId
     * @param float       $totalAmount    Tổng sau giảm
     * @param string|null $couponCode     Mã đã áp (hoặc null)
     * @param int         $discountAmount Tiền giảm (₫)
     * @return int
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
     * Lấy order theo ID
     *
     * @param int $orderId
     * @return array|null
     */
    public function findOrderById(int $orderId): ?array
    {
        $sql = "
            SELECT *
            FROM {$this->table}
            WHERE id = :oid
            LIMIT 1
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':oid' => $orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Lấy danh sách orders của 1 user, có coupon_code + discount_amount
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
                s.address AS shipping_address
            FROM {$this->table} o
            LEFT JOIN shippings s ON s.order_id = o.id
            WHERE o.user_id = :uid
            ORDER BY o.created_at DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
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
}
