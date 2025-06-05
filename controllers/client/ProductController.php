<?php
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
        $productId = isset($_GET['id']) ? (int) $_GET['id'] : null;
        if (!$productId) {
            header('Location: ' . BASE_URL);
            exit;
        }
        $productModel = new Product();
        $product = $productModel->getProductDetail($productId);
        if (!$product) {
            header('Location: /');
            exit;
        }
        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'product-detail.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php';
    }
}