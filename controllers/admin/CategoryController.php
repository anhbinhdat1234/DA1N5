<?php
require_once 'models/Category.php';

class CategoryController {
    private $categoryModel;

    public function __construct() {
        $this->categoryModel = new Category();
    }

    public function index() {
        $categories = $this->categoryModel->getAll();
        require 'views/admin/categories/index.php';
    }

    public function create() {
        require PATH_VIEW_ADMIN . 'categories/create.php';
    }

    public function store() {
        $data = ['name' => $_POST['name']];
        $this->categoryModel->create($data);
        header('Location: ' . BASE_URL_ADMIN . '&action=categories-index&success=1');
    }

    public function edit($id) {
    // Kiểm tra ID tồn tại
    if (!$id || !is_numeric($id)) {
        header('Location: ' . BASE_URL_ADMIN . '&action=categories-index&error=ID không hợp lệ');
        exit();
    }

    // Lấy dữ liệu danh mục
    $category = $this->categoryModel->findById($id);
    
    if (!$category) {
        header('Location: ' . BASE_URL_ADMIN . '&action=categories-index&error=Không tìm thấy danh mục');
        exit();
    }

    require PATH_VIEW_ADMIN . 'categories/edit.php';
}

    public function update($id) {
        $data = ['name' => $_POST['name']];
        $this->categoryModel->updateCategory($id, $data);
        header('Location: ' . BASE_URL_ADMIN . '&action=categories-index&success=1');
    }

   public function delete($id) {
    if (!$id || !is_numeric($id)) {
        header('Location: ' . BASE_URL_ADMIN . '&action=categories-index&error=ID không hợp lệ');
        exit();
    }

    $category = $this->categoryModel->findById($id);
    if (!$category) {
        header('Location: ' . BASE_URL_ADMIN . '&action=categories-index&error=Không tìm thấy danh mục');
        exit();
    }

    $this->categoryModel->deleteCategory($id);
    header('Location: ' . BASE_URL_ADMIN . '&action=categories-index&success=1');
}

}
