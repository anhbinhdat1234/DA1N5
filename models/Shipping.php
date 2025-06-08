<?php
// models/Shipping.php

class Shipping extends BaseModel
{
    protected $table = 'shippings';

    /**
     * Lưu địa chỉ & phone cho đơn hàng
     *
     * @param int    $orderId
     * @param string $address
     * @param string $phone
     * @return bool
     */
    public function createShipping(int $orderId, string $address, string $phone): bool
    {
        $sql = "
            INSERT INTO {$this->table}
                (order_id, address, phone)
            VALUES
                (:oid, :addr, :phone)
        ";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':oid'   => $orderId,
            ':addr'  => $address,
            ':phone' => $phone,
        ]);
    }
}
