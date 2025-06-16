<?php
class ReviewController
{
  protected $reviewModel;

  public function __construct()
  {
    $this->reviewModel = new Review();
  }

  // 1. Hiển thị tất cả bình luận (theo tên user, tên sản phẩm)
  public function index()
  {
    $reviews = $this->reviewModel->getAllWithUserAndProduct();
    include PATH_VIEW_ADMIN . 'review/index.php';
  }

  // 2. Ẩn/Hiện bình luận (chỉ admin hoặc chủ nhân)
  public function toggleHidden()
  {
    $reviewId = (int)$_GET['id'];
    $review = $this->reviewModel->getReviewById($reviewId);
    $userId = $_SESSION['user']['id'] ?? 0;
    $role = $_SESSION['user']['role'] ?? 'user'; // đúng với session bạn đang dùng
    if ($userId == $review['user_id'] || $role == 'admin') {
      $newHidden = $review['is_hidden'] ? 0 : 1;
      $this->reviewModel->toggleHidden($reviewId, $newHidden);
    }
    header('Location: ' . BASE_URL_ADMIN . '&action=review-index');
    exit();
  }

  // 3. Xóa bình luận (chỉ admin hoặc chủ nhân)
  public function delete()
  {
    $reviewId = (int)$_GET['id'];
    $review = $this->reviewModel->getReviewById($reviewId);
    $userId = $_SESSION['user']['id'] ?? 0;
    $role = $_SESSION['user']['role'] ?? 'user';
    if ($userId == $review['user_id'] || $role == 'admin') {
      $this->reviewModel->deleteReview($reviewId);
    }
    header('Location: ' . BASE_URL_ADMIN . '&action=review-index');
    exit();
  }
}
