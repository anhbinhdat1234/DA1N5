<?php
class Review extends BaseModel
{
    protected $table = 'reviews';

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

    // Xóa review
    public function deleteReview($reviewId)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $reviewId]);
    }
}
