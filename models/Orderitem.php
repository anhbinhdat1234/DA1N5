<?php
class OrderItem extends BaseModel
{
    protected $table = 'order_items';

    public function createItems($orderId, $items)
    {
        try {
            $sql = "INSERT INTO {$this->table} 
                    (order_id, product_variant_id, quantity, price) 
                    VALUES 
                    (:order_id, :product_variant_id, :quantity, :price)";
            
            $stmt = $this->pdo->prepare($sql);
            
            foreach ($items as $item) {
                $stmt->execute([
                    'order_id' => $orderId,
                    'product_variant_id' => $item['product_variant_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }
            
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getItemsByOrder($orderId)
    {
        $sql = "SELECT oi.*, p.name as product_name, pv.size, pv.color 
                FROM {$this->table} oi
                JOIN product_variants pv ON oi.product_variant_id = pv.id
                JOIN products p ON pv.product_id = p.id
                WHERE oi.order_id = :order_id";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['order_id' => $orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateQuantity($itemId, $quantity)
    {
        $stmt = $this->pdo->prepare("UPDATE order_items SET quantity = ? WHERE id = ?");
        $stmt->execute([$quantity, $itemId]);
    }


    public function deleteItem($itemId)
    {
        $stmt = $this->pdo->prepare("DELETE FROM order_items WHERE id = ?");
        $stmt->execute([$itemId]);
    }


}