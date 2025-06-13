<?php
// controllers/admin/ProductController.php

require_once PATH_MODEL . 'Product.php';
require_once PATH_MODEL . 'Category.php';
require_once PATH_MODEL . 'ProductImage.php';

class ProductController
{
    private Product $product;
    private Category $category;
    private ProductImage $imageModel;

    public function __construct()
    {
        $this->product    = new Product();
        $this->category   = new Category();
        $this->imageModel = new ProductImage();
    }

    /**
     * Danh sách sản phẩm (Admin)
     */
    public function index()
    {
        $view     = 'product/index';
        $title    = 'Danh sách sản phẩm';
        $products = $this->product->getWithCategory();

        // Gắn thêm thumbnail (lấy ảnh đầu tiên nếu có)
        foreach ($products as &$p) {
            $imgs = $this->imageModel->findByProductId((int)$p['id']);
            $p['image_url'] = $imgs[0]['image_url'] 
                ?? '/assets/client/assets/img/default.png';
        }
        unset($p);

        require_once PATH_VIEW_ADMIN . '/main.php';
    }

    /**
     * Hiển thị form thêm sản phẩm mới
     */
    public function create()
    {
        $view          = 'product/create';
        $title         = 'Thêm sản phẩm';
        $categories    = $this->category->select();
        $categoryPluck = array_column($categories, 'name', 'id');
        require_once PATH_VIEW_ADMIN . '/main.php';
    }

    /**
     * Xử lý lưu sản phẩm mới
     */
    public function store()
    {
        try {
            $data = $_POST;
            $_SESSION['errors'] = [];

            // 1) Validate cơ bản
            if (empty($data['name'])) {
                $_SESSION['errors']['name'] = 'Tên sản phẩm bắt buộc.';
            }
            if (empty($data['price']) || !is_numeric($data['price'])) {
                $_SESSION['errors']['price'] = 'Giá phải là số.';
            }
            if (empty($data['category_id'])) {
                $_SESSION['errors']['category_id'] = 'Chọn danh mục.';
            }
            if (!empty($_SESSION['errors'])) {
                throw new Exception;
            }

            // 2) Chèn product & lấy ID mới
            $data['created_at'] = date('Y-m-d H:i:s');
            $newId = $this->product->insertGetId($data);

            // 3) Xử lý upload nhiều ảnh nếu có
            if (!empty($_FILES['images'])) {
                $files = $_FILES['images'];
                for ($i = 0; $i < count($files['name']); $i++) {
                    if ($files['error'][$i] === UPLOAD_ERR_OK) {
                        $ext      = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                        $filename = uniqid('prod_') . '.' . $ext;
                        $dest     = __DIR__ . '/../../public/uploads/products/' . $filename;
                        // chỉ nhận hình
                        if (!in_array(strtolower($ext), ['jpg','jpeg','png','gif'])) {
                            continue;
                        }
                        move_uploaded_file($files['tmp_name'][$i], $dest);
                        $this->imageModel->create([
                            'product_id' => $newId,
                            'image_url'  => '/uploads/products/' . $filename,
                        ]);
                    }
                }
            }

            $_SESSION['success'] = true;
            $_SESSION['msg']     = 'Thêm sản phẩm thành công!';
        } catch (Exception $e) {
            $_SESSION['success'] = false;
            $_SESSION['msg']     = 'Lỗi: dữ liệu không hợp lệ.';
        }

        header('Location: ' . BASE_URL_ADMIN . '&action=product-index');
        exit;
    }

    /**
     * Hiển thị form sửa sản phẩm
     */
    public function edit()
    {
        $id            = (int)($_GET['id'] ?? 0);
        $product       = $this->product->find('*', 'id = :id', ['id' => $id]);
        $categories    = $this->category->select();
        $categoryPluck = array_column($categories, 'name', 'id');
        $productImages = $this->imageModel->findByProductId($id);

        $view  = 'product/edit';
        $title = 'Sửa sản phẩm';
        require_once PATH_VIEW_ADMIN . '/main.php';
    }

    /**
     * Xử lý cập nhật sản phẩm
     */
    public function update()
    {
        $id = (int)($_GET['id'] ?? 0);
        try {
            $data = $_POST;
            $_SESSION['errors'] = [];

            // Validate
            if (empty($data['name'])) {
                $_SESSION['errors']['name'] = 'Tên sản phẩm bắt buộc.';
            }
            if (empty($data['price']) || !is_numeric($data['price'])) {
                $_SESSION['errors']['price'] = 'Giá phải là số.';
            }
            if (!empty($_SESSION['errors'])) {
                throw new Exception;
            }

            // Cập nhật product
            $data['updated_at'] = date('Y-m-d H:i:s');
            $this->product->update($data, 'id = :id', ['id' => $id]);

            // Upload thêm ảnh mới
            if (!empty($_FILES['images'])) {
                $files = $_FILES['images'];
                for ($i = 0; $i < count($files['name']); $i++) {
                    if ($files['error'][$i] === UPLOAD_ERR_OK) {
                        $ext      = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                        $filename = uniqid('prod_') . '.' . $ext;
                        $dest     = __DIR__ . '/../../public/uploads/products/' . $filename;
                        if (!in_array(strtolower($ext), ['jpg','jpeg','png','gif'])) {
                            continue;
                        }
                        move_uploaded_file($files['tmp_name'][$i], $dest);
                        $this->imageModel->create([
                            'product_id' => $id,
                            'image_url'  => '/uploads/products/' . $filename,
                        ]);
                    }
                }
            }

            $_SESSION['success'] = true;
            $_SESSION['msg']     = 'Cập nhật thành công!';
        } catch (Exception $e) {
            $_SESSION['success'] = false;
            $_SESSION['msg']     = 'Lỗi: dữ liệu không hợp lệ.';
        }

        header('Location: ' . BASE_URL_ADMIN . '&action=product-index');
        exit;
    }

    /**
     * Hiển thị chi tiết sản phẩm
     */
    public function show()
    {
        $id      = (int)($_GET['id'] ?? 0);
        $product = $this->product->getProductDetail($id);
        $images  = $this->imageModel->findByProductId($id);

        $view  = 'product/show';
        $title = 'Chi tiết sản phẩm';
        require_once PATH_VIEW_ADMIN . '/main.php';
    }

    /**
     * Xóa sản phẩm và ảnh liên quan
     */
    public function delete()
    {
        $id = (int)($_GET['id'] ?? 0);

        if (!$id) {
            $_SESSION['success'] = false;
            $_SESSION['msg']     = 'Thiếu ID sản phẩm!';
        } else {
            // Xóa file ảnh trên server
            $imgs = $this->imageModel->findByProductId($id);
            foreach ($imgs as $img) {
                @unlink(__DIR__ . '/../../public' . $img['image_url']);
            }
            // Xóa record trong product_images
            $this->imageModel->deleteByProductId($id);
            // Xóa product
            $ok = $this->product->delete($id);

            $_SESSION['success'] = $ok;
            $_SESSION['msg']     = $ok ? 'Xóa thành công!' : 'Xóa thất bại!';
        }

        header('Location: ' . BASE_URL_ADMIN . '&action=product-index');
        exit;
    }
}
