<?php
class Order extends BaseModel
{
protected $table = 'orders';

    public function getAll()
    {
        $sql = "SELECT o.*, u.name as user_name
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            ORDER BY o.id DESC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }


    public function findById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteById($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }


    public function updateStatus($id, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE {$this->table} SET status = :status WHERE id = :id");
        return $stmt->execute(['status' => $status, 'id' => $id]);
    }
     public function createOrder($userId, $totalAmount, $couponCode = null, $discountAmount = 0)
    {
        try {
            $this->pdo->beginTransaction();

            $orderData = [
                'user_id' => $userId,
                'total' => $totalAmount,
                'coupon_code' => $couponCode,
                'discount_amount' => $discountAmount,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s')
            ];

            $sql = "INSERT INTO {$this->table} 
                    (user_id, total, coupon_code, discount_amount, status, created_at) 
                    VALUES 
                    (:user_id, :total, :coupon_code, :discount_amount, :status, :created_at)";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($orderData);
            
            $orderId = $this->pdo->lastInsertId();

            $this->pdo->commit();
            return $orderId;
            
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function reduceStock($cartItems)
    {
        try {
            $this->pdo->beginTransaction();
            
            $sql = "UPDATE product_variants SET stock = stock - :quantity WHERE id = :variant_id";
            $stmt = $this->pdo->prepare($sql);

            foreach ($cartItems as $item) {
                $stmt->execute([
                    'quantity' => $item['quantity'],
                    'variant_id' => $item['product_variant_id']
                ]);
            }
            
            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function findOrderById($id)
    {
        $sql = "SELECT o.*, u.name as user_name
                FROM orders o
                LEFT JOIN users u ON o.user_id = u.id
                WHERE o.id = :id";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //NGƯỜI DÙNG CÓ THỂ XEM ĐƠN HÀNG CỦA HỌHỌ
    
    public function getOrdersByUser($userId)
    {
        $sql = "SELECT o.*, 
                    COUNT(oi.id) as item_count, 
                    SUM(oi.quantity * oi.price) as total_amount
                FROM orders o
                LEFT JOIN order_items oi ON o.id = oi.order_id
                WHERE o.user_id = :user_id
                GROUP BY o.id
                ORDER BY o.created_at DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderDetails($orderId, $userId)
    {
        $sql = "SELECT o.*, s.address, s.phone, s.note, s.status as shipping_status
                FROM orders o
                LEFT JOIN shippings s ON o.id = s.order_id
                WHERE o.id = :order_id AND o.user_id = :user_id";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'order_id' => $orderId,
            'user_id' => $userId
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}