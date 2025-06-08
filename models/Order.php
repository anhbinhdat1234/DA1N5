<?php
// models/Order.php

class Order extends BaseModel
{
    protected $table = 'orders';

    /**
     * Tạo mới order, trả về order_id
     *
     * @param int   $userId
     * @param float $totalAmount
     * @return int
     */
    public function createOrder(int $userId, float $totalAmount): int
    {
        $sql = "
            INSERT INTO {$this->table}
                (user_id, total, created_at)
            VALUES
                (:uid, :total, NOW())
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':uid'   => $userId,
            ':total' => $totalAmount,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Lấy order theo ID
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
        public function getOrdersByUser(int $userId): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = :uid ORDER BY created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
