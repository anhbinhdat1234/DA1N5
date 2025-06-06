<?php
// models/Order.php

class Order extends BaseModel
{
    protected $table = 'orders';

    /**
     * Tạo một đơn hàng mới, trả về order_id vừa tạo
     *
     * @param int    $userId
     * @param string $recipientName
     * @param string $address
     * @param string $phone
     * @param string $note
     * @param float  $totalAmount
     * @return int   $orderId
     * @throws Exception nếu có lỗi
     */
    public function createOrder(int $userId, string $recipientName, string $address, string $phone, string $note, float $totalAmount): int
    {
        $sql = "
            INSERT INTO {$this->table} 
                (user_id, recipient_name, address, phone, note, total_amount, created_at) 
            VALUES 
                (:uid, :rname, :addr, :phone, :note, :total, NOW())
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':uid'    => $userId,
            ':rname'  => $recipientName,
            ':addr'   => $address,
            ':phone'  => $phone,
            ':note'   => $note,
            ':total'  => $totalAmount
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    /**
     * Lấy thông tin một đơn hàng theo ID
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
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        return $order ?: null;
    }

    /**
     * Lấy danh sách order của một user (nếu cần)
     *
     * @param int $userId
     * @return array
     */
    public function getOrdersByUser(int $userId): array
    {
        $sql = "
            SELECT *
            FROM {$this->table}
            WHERE user_id = :uid
            ORDER BY created_at DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
