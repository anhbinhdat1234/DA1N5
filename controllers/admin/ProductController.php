<?php
require_once PATH_MODEL . 'Product.php';
require_once PATH_MODEL . 'Category.php';

class ProductController
{
    private $product;
    private $category;

    public function __construct()
    {
        $this->product = new Product();
        $this->category = new Category();
    }

    public function index()
    {
        $view = 'product/index';
        $title = 'Danh sách sản phẩm';
        $products = $this->product->getWithCategory();
        require_once PATH_VIEW_ADMIN . '/main.php';
    }

    public function create()
    {
        $view = 'product/create';
        $title = 'Thêm sản phẩm';
        $categories = $this->category->select();
        $categoryPluck = array_column($categories, 'name', 'id');
        require_once PATH_VIEW_ADMIN . 'main.php';
    }

    public function store()
    {
        try {
            $data = $_POST;
            $this->product->insert($data);
            $_SESSION['success'] = true;
            $_SESSION['msg'] = 'Thêm sản phẩm thành công!';
        } catch (Exception $e) {
            $_SESSION['success'] = false;
            $_SESSION['msg'] = 'Lỗi: ' . $e->getMessage();
        }
        header('Location: ' . BASE_URL_ADMIN . '&action=product-index');
        exit;
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        $product = $this->product->find('*', 'id = :id', ['id' => $id]);
        $categories = $this->category->select();
        $categoryPluck = array_column($categories, 'name', 'id');

        $view = 'product/edit';
        $title = 'Sửa sản phẩm';
        require_once PATH_VIEW_ADMIN . 'main.php';
    }

    public function update()
    {
        $id = $_GET['id'] ?? null;
        try {
            $data = $_POST;
            $this->product->update($data, 'id = :id', ['id' => $id]);
            $_SESSION['success'] = true;
            $_SESSION['msg'] = 'Cập nhật thành công';
        } catch (Exception $e) {
            $_SESSION['success'] = false;
            $_SESSION['msg'] = 'Lỗi: ' . $e->getMessage();
        }
        header('Location: ' . BASE_URL_ADMIN . '&action=product-index');
        exit;
    }

    public function show()
    {
        $id = $_GET['id'] ?? null;
        $product = $this->product->getProductDetail($id); // Dùng hàm mới
        $view = 'product/show';
        $title = 'Chi tiết sản phẩm';
        require_once PATH_VIEW_ADMIN . 'main.php';
    }


    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['success'] = false;
            $_SESSION['msg'] = 'Thiếu id sản phẩm!';
        } else {
            $result = $this->product->deleteProductWithRelations($id);
            if ($result) {
                $_SESSION['success'] = true;
                $_SESSION['msg'] = 'Xóa thành công!';
            } else {
                $_SESSION['success'] = false;
                $_SESSION['msg'] = 'Không xóa được. Có thể do lỗi liên kết hoặc server!';
            }
        }
        header('Location: ' . BASE_URL_ADMIN . '&action=product-index');
        exit;
    }
}
