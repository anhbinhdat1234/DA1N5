<?php
class Review extends BaseModel
{
    protected $table = 'reviews';
    // Lấy các review theo sản phẩm
    public function getByProductId($productId)
    {
        $sql = "SELECT r.*, u.name AS user_name
                FROM {$this->table} r
                JOIN users u ON u.id = r.user_id
                WHERE r.product_id = :product_id AND r.is_hidden = 0
                ORDER BY r.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':product_id' => $productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy danh sách review + tên user + tên sản phẩm
    public function getAllWithUserAndProduct()
    {
        $sql = "SELECT r.*, u.name AS user_name, p.name AS product_name
                FROM {$this->table} r
                JOIN users u ON u.id = r.user_id
                JOIN products p ON p.id = r.product_id
                ORDER BY r.created_at DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy review theo id
    public function getReviewById(int $reviewId)
    {
        $sql = "SELECT r.*, u.name AS user_name, p.name AS product_name
                FROM {$this->table} r
                JOIN users u ON u.id = r.user_id
                JOIN products p ON p.id = r.product_id
                WHERE r.id = :rid";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':rid' => $reviewId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Đổi trạng thái ẩn/hiện
    public function toggleHidden($reviewId, $isHidden)
    {
        $sql = "UPDATE {$this->table} SET is_hidden = :is_hidden WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':is_hidden' => $isHidden ? 1 : 0,
            ':id' => $reviewId
        ]);
    }
    // Tạo review mới
    public function createReview($userId, $productId, $rating, $content)
    {
        $sql = "INSERT INTO {$this->table} (user_id, product_id, rating, content, created_at)
                VALUES (:user_id, :product_id, :rating, :content, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':user_id' => $userId,
            ':product_id' => $productId,
            ':rating' => $rating,
            ':content' => $content
        ]);
    }

    // Xóa review
    public function deleteReview($reviewId)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $reviewId]);
    }
}
