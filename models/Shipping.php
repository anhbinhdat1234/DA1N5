<?php
class Shipping extends BaseModel
{
    protected $table = 'shippings';

    public function createShipping($orderId, $address, $phone, $note = null)
    {
        $sql = "INSERT INTO {$this->table} 
                (order_id, address, phone, note, status, created_at) 
                VALUES 
                (:order_id, :address, :phone, :note, 'pending', NOW())";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'order_id' => $orderId,
            'address' => $address,
            'phone' => $phone,
            'note' => $note
        ]);
    }
}