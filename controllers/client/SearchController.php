<?php
class SearchController
{
    public function search()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Lấy từ khóa từ GET
        $keyword = trim($_GET['keyword'] ?? '');

        // Nếu không có keyword, chuyển về trang chủ
        if ($keyword === '') {
            header('Location: ' . BASE_URL);
            exit;
        }

        // Gọi model để tìm sản phẩm
        $productModel = new Product();
        $products = $productModel->searchByKeyword($keyword);

        // Render header, view kết quả và footer
        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'search-results.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php';
    }
}
