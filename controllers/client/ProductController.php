<?php
// controllers/ProductController.php

class ProductController
{
    public function index()
    {
        $categoryId = isset($_GET['category_id']) ? (int) $_GET['category_id'] : null;
        $productModel = new Product();
        
        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'product/index.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php';
    }

    public function detail()
    {
        // 1. Bắt đầu session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 2. Lấy product ID & kiểm tra
        $productId = isset($_GET['id']) ? (int) $_GET['id'] : null;
        if (!$productId) {
            header('Location: ' . BASE_URL);
            exit;
        }

        // 3. Lấy chi tiết sản phẩm
        $productModel = new Product();
        $product = $productModel->getProductDetail($productId);
        if (!$product) {
            header('Location: ' . BASE_URL);
            exit;
        }

        // 4. Nạp Review model và lấy reviews
        require_once PATH_MODEL . 'Review.php'; // hoặc PSR-4 autoload sẽ tự include
        $reviewModel = new Review();
        $reviews = $reviewModel->getByProductId($productId);

        // 5. Xử lý form POST nếu user đã đăng nhập
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_SESSION['user'])) {
                $rating  = (int) ($_POST['rating']  ?? 0);
                $content = trim($_POST['content'] ?? '');
                if ($rating >= 1 && $rating <= 5 && $content !== '') {
                    $reviewModel->createReview(
                        $_SESSION['user']['id'],
                        $productId,
                        $rating,
                        $content
                    );
                }
                // reload để tránh repost dữ liệu
                header('Location: ' . BASE_URL . '?action=product_detail&id=' . $productId);
                exit;
            } else {
                // nếu chưa login, chuyển hướng về login
                header('Location: ' . BASE_URL . '?action=login');
                exit;
            }
        }

        // 6. Render view, truyền $product và $reviews
        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'product-detail.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php';
    }
}
