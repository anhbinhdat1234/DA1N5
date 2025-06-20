<?php
// models/Order.php

require_once __DIR__ . '/BaseModel.php';

class Order extends BaseModel
{
    protected $table = 'orders';

    /**
     * Tạo mới order, trả về order_id
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
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Lấy order theo ID, kèm thông tin giao hàng
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
     * Lấy danh sách orders của 1 user, có thông tin giao hàng
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
     * Cập nhật địa chỉ và phone cho đơn hàng (bảng shippings)
     */
    public function updateAddress(int $orderId, string $newAddress, string $newPhone): bool
    {
        $sql = "
            UPDATE shippings
               SET address = :addr,
                   phone   = :phone
             WHERE order_id = :oid
        ";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':addr'  => $newAddress,
            ':phone' => $newPhone,
            ':oid'   => $orderId,
        ]);
    }

    /**
     * Đánh dấu hủy đơn
     */
    public function cancel(int $orderId): bool
    {
        $sql  = "UPDATE {$this->table} SET status = 'cancelled' WHERE id = :oid";
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
    public function getAllWithUser(): array
    {
        $sql = "
      SELECT
        o.*,
        u.name AS user_name
      FROM {$this->table} o
      LEFT JOIN users u ON o.user_id = u.id
      ORDER BY o.created_at DESC
    ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteById(int $orderId): bool
    {
        try {
            $this->pdo->beginTransaction();

            // 1. Xóa các order_items liên quan
            $stmt1 = $this->pdo->prepare("DELETE FROM order_items WHERE order_id = :id");
            $stmt1->execute([':id' => $orderId]);

            // 2. Xóa các shippings liên quan
            $stmt2 = $this->pdo->prepare("DELETE FROM shippings WHERE order_id = :id");
            $stmt2->execute([':id' => $orderId]);

            // 3. Xóa chính đơn hàng
            $stmt3 = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
            $stmt3->execute([':id' => $orderId]);

            $this->pdo->commit();
            return true;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
    public function findById(int $id): ?array
    {
        return $this->findOrderById($id);
    }
    public function updateStatus(int $orderId, string $status): bool
    {
        $sql = "
            UPDATE {$this->table}
               SET status = :status
             WHERE id = :id
        ";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':status' => $status,
            ':id'     => $orderId,
        ]);
    }
    public function getOrderDetails(int $orderId, int $userId): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM {$this->table}
             WHERE id = :oid AND user_id = :uid
             LIMIT 1"
        );
        $stmt->execute([
            ':oid' => $orderId,
            ':uid' => $userId,
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
     // ✅ Thống kê: Đếm tổng số đơn hàng
    public function countOrders(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM {$this->table}");
        return (int)$stmt->fetchColumn();
    }

    // ✅ Thống kê: Tổng doanh thu từ các đơn đã hoàn thành
    public function totalRevenue(): float
    {
        $stmt = $this->pdo->query("SELECT SUM(total) FROM {$this->table} WHERE status = 'completed'");
        return (float)$stmt->fetchColumn();
    }

    // ✅ Thống kê: Doanh thu theo từng tháng trong năm
    public function getMonthlyRevenue(int $year): array
    {
        $stmt = $this->pdo->prepare("
            SELECT MONTH(created_at) AS month, SUM(total) AS revenue
            FROM {$this->table}
            WHERE YEAR(created_at) = :year AND status = 'completed'
            GROUP BY MONTH(created_at)
            ORDER BY MONTH(created_at)
        ");
        $stmt->execute([':year' => $year]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $monthlyRevenue = array_fill(1, 12, 0); // Khởi tạo doanh thu 12 tháng = 0
        foreach ($results as $row) {
            $monthlyRevenue[(int)$row['month']] = (float)$row['revenue'];
        }
        return $monthlyRevenue;
    }
}
