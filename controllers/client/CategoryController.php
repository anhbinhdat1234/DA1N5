<?php

class CategoryController
{
    public function index()
    {
        $categoryId = isset($_GET['id']) ? (int) $_GET['id'] : null;
        if (!$categoryId) {
            header('Location: ' . BASE_URL);
            exit;
        }
        $categoryModel = new Category();
        $productModel = new Product();
        $category = $categoryModel->find('*', "id = $categoryId");
        $products = $productModel->getProductByCategoryId($categoryId);
        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'categories.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php';
    }
}