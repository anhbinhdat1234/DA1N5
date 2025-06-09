<?php
// models/Shipping.php

require_once __DIR__ . '/BaseModel.php';

class Shipping extends BaseModel
{
    protected $table = 'shippings';

    /**
     * Lưu địa chỉ, phone và ghi chú cho đơn hàng
     *
     * @param int         $orderId
     * @param string      $address
     * @param string      $phone
     * @param string|null $note
     * @return bool
     */
    public function createShipping(int $orderId, string $address, string $phone, ?string $note = null): bool
    {
        $sql = "
            INSERT INTO {$this->table}
                (order_id, address, phone, note, created_at)
            VALUES
                (:oid, :addr, :phone, :note, NOW())
        ";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':oid'   => $orderId,
            ':addr'  => $address,
            ':phone' => $phone,
            ':note'  => $note,
        ]);
    }

    /**
     * Cập nhật địa chỉ và phone cho đơn hàng
     *
     * @param int    $orderId
     * @param string $address
     * @param string $phone
     * @return bool
     */
    public function updateShipping(int $orderId, string $address, string $phone): bool
    {
        $sql = "
            UPDATE {$this->table}
               SET address = :addr,
                   phone   = :phone
             WHERE order_id = :oid
        ";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':addr'  => $address,
            ':phone' => $phone,
            ':oid'   => $orderId,
        ]);
    }

    /**
     * Lấy thông tin giao hàng theo order_id
     */
    public function findByOrder(int $orderId): ?array
    {
        $sql = "
            SELECT *
            FROM {$this->table}
            WHERE order_id = :oid
            LIMIT 1
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':oid' => $orderId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
