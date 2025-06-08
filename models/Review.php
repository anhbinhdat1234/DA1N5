<?php
// models/Review.php

class Review extends BaseModel
{
    protected $table = 'reviews';

    /**
     * Lấy danh sách review của một sản phẩm
     *
     * @param int $productId
     * @return array
     */
    public function getByProductId(int $productId): array
    {
        $sql = "SELECT r.*, u.name AS user_name
                FROM {$this->table} r
                JOIN users u ON u.id = r.user_id
                WHERE r.product_id = :pid
                ORDER BY r.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':pid' => $productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Thêm review mới
     */
    public function createReview(int $userId, int $productId, int $rating, string $content): void
    {
        $sql = "INSERT INTO {$this->table} (user_id, product_id, rating, content, created_at)
                VALUES (:uid, :pid, :rate, :cont, NOW())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':uid'  => $userId,
            ':pid'  => $productId,
            ':rate' => $rating,
            ':cont' => $content,
        ]);
    }
}
