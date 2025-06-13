<?php
require_once PATH_MODEL . 'Product.php';
require_once PATH_MODEL . 'Category.php';
require_once PATH_MODEL . 'ProductImage.php';

class ProductController
{
    private Product      $product;
    private Category     $category;
    private ProductImage $imageModel;

    public function __construct()
    {
        $this->product    = new Product();
        $this->category   = new Category();
        $this->imageModel = new ProductImage();
    }

    public function index()
    {
        // 1. Chuẩn bị data
        $products = $this->product->getWithCategory();
        foreach ($products as &$p) {
            $imgs = $this->imageModel->findByProductId((int)$p['id']);
            $p['image_url'] = $imgs[0]['image_url'] 
                ?? '/assets/client/assets/img/default.png';
        }
        unset($p);

        // 2. Thiết lập view + title
        $view  = 'product/index';
        $title = 'Danh sách sản phẩm';

        // 3. Include layout chính
        require_once PATH_VIEW_ADMIN_MAIN;
    }

    public function create()
    {
        $categories = $this->category->select();

        $view  = 'product/create';
        $title = 'Thêm sản phẩm mới';

        require_once PATH_VIEW_ADMIN_MAIN;
    }

    public function store()
    {
        // Lấy data gốc
        $data = $_POST;
        $_SESSION['errors'] = [];
        $_SESSION['old']    = $data;

        // 1) VALIDATION
        if (empty($data['name'])) {
            $_SESSION['errors']['name'] = 'Tên sản phẩm bắt buộc.';
        }
        if (empty($data['price']) || !is_numeric($data['price'])) {
            $_SESSION['errors']['price'] = 'Giá phải là số.';
        }
        if (empty($data['category_id'])) {
            $_SESSION['errors']['category_id'] = 'Chọn danh mục.';
        }

        // Nếu lỗi, include lại form (không redirect)
        if (!empty($_SESSION['errors'])) {
            $view       = 'product/create';
            $title      = 'Thêm sản phẩm mới';
            $categories = $this->category->select();
            require_once PATH_VIEW_ADMIN_MAIN;
            return;
        }

        try {
            // 2) CHUẨN BỊ MẢNG CHÈN VÀO products
            $productData = [
                'name'        => $data['name'],
                'price'       => $data['price'],
                'description' => $data['description'] ?? null,
                'category_id' => $data['category_id'],
                'created_at'  => date('Y-m-d H:i:s'),
            ];

            // 3) Insert và lấy ID mới
            $newId = $this->product->insertGetId($productData);

            // 4) XỬ LÝ FILE UPLOAD
            if (!empty($_FILES['images'])) {
                $files = $_FILES['images'];
                for ($i = 0; $i < count($files['name']); $i++) {
                    if ($files['error'][$i] === UPLOAD_ERR_OK) {
                        $ext  = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                        $fn   = uniqid('prod_') . ".$ext";
                        $dest = PATH_ASSETS_UPLOADS . "products/$fn";
                        if (in_array(strtolower($ext), ['jpg','jpeg','png','gif'])) {
                            move_uploaded_file($files['tmp_name'][$i], $dest);
                            $this->imageModel->create([
                                'product_id' => $newId,
                                'image_url'  => "/assets/uploads/products/$fn",
                            ]);
                        }
                    }
                }
            }

            // 5) XỬ LÝ EXTERNAL-URL & DATA-URI
            if (!empty($data['external_images'])) {
                foreach (explode("\n", trim($data['external_images'])) as $line) {
                    $url    = trim($line);
                    $isHttp = filter_var($url, FILTER_VALIDATE_URL);
                    $isData = preg_match('#^data:image/[a-zA-Z]+;base64,#', $url);
                    if ($isHttp || $isData) {
                        $this->imageModel->create([
                            'product_id' => $newId,
                            'image_url'  => $url,
                        ]);
                    }
                }
            }

            $_SESSION['success'] = true;
            $_SESSION['msg']     = 'Thêm sản phẩm thành công!';
        } catch (Exception $e) {
            $_SESSION['success'] = false;
            $_SESSION['msg']     = 'Lỗi hệ thống: ' . $e->getMessage();
        }

        // 6) Cleanup và redirect
        unset($_SESSION['errors'], $_SESSION['old']);
        header('Location: ' . BASE_URL_ADMIN . '&action=product-index');
        exit;
    }



    public function edit()
    {
        $id         = (int)($_GET['id'] ?? 0);
        $product    = $this->product->find('*', 'id = :id', ['id' => $id]);
        $categories = $this->category->select();
        $images     = $this->imageModel->findByProductId($id);

        $view  = 'product/edit';
        $title = "Sửa sản phẩm #$id";

        require_once PATH_VIEW_ADMIN_MAIN;
    }

public function update()
    {
        $id = (int)($_GET['id'] ?? 0);
        $data = $_POST;
        $_SESSION['errors'] = [];
        $_SESSION['old']    = $data;

        // 1) VALIDATION
        if (empty($data['name'])) {
            $_SESSION['errors']['name'] = 'Tên sản phẩm bắt buộc.';
        }
        if (empty($data['price']) || !is_numeric($data['price'])) {
            $_SESSION['errors']['price'] = 'Giá phải là số.';
        }

        if (!empty($_SESSION['errors'])) {
            // Load lại form edit với errors + old data
            $view       = 'product/edit';
            $title      = "Sửa sản phẩm #$id";
            $product    = $this->product->find('*', 'id = :id', ['id' => $id]);
            $categories = $this->category->select();
            $images     = $this->imageModel->findByProductId($id);
            require_once PATH_VIEW_ADMIN_MAIN;
            return;
        }

        try {
            // 2) Chuẩn bị mảng cập nhật
            $updateData = [
                'name'        => $data['name'],
                'price'       => $data['price'],
                'description' => $data['description'] ?? null,
                'category_id' => $data['category_id'],
                'updated_at'  => date('Y-m-d H:i:s'),
            ];

            // 3) Cập nhật bảng products
            $this->product->update($updateData, 'id = :id', ['id' => $id]);

            // 4) Upload file mới (nếu có)
            if (!empty($_FILES['images'])) {
                $files = $_FILES['images'];
                for ($i = 0; $i < count($files['name']); $i++) {
                    if ($files['error'][$i] === UPLOAD_ERR_OK) {
                        $ext  = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                        $fn   = uniqid('prod_') . ".$ext";
                        $dest = PATH_ASSETS_UPLOADS . "products/$fn";
                        if (in_array(strtolower($ext), ['jpg','jpeg','png','gif'])) {
                            move_uploaded_file($files['tmp_name'][$i], $dest);
                            $this->imageModel->create([
                                'product_id' => $id,
                                'image_url'  => "/assets/uploads/products/$fn",
                            ]);
                        }
                    }
                }
            }

            // 5) Xử lý external_images
            if (!empty($data['external_images'])) {
                foreach (explode("\n", trim($data['external_images'])) as $line) {
                    $url    = trim($line);
                    $isHttp = filter_var($url, FILTER_VALIDATE_URL);
                    $isData = preg_match('#^data:image/[a-zA-Z]+;base64,#', $url);
                    if ($isHttp || $isData) {
                        $this->imageModel->create([
                            'product_id' => $id,
                            'image_url'  => $url,
                        ]);
                    }
                }
            }

            $_SESSION['success'] = true;
            $_SESSION['msg']     = 'Cập nhật thành công!';
        } catch (Exception $e) {
            $_SESSION['success'] = false;
            $_SESSION['msg']     = 'Lỗi hệ thống: ' . $e->getMessage();
        }

        unset($_SESSION['errors'], $_SESSION['old']);
        header('Location: ' . BASE_URL_ADMIN . '&action=product-index');
        exit;
    }


    public function show()
    {
        $id      = (int)($_GET['id'] ?? 0);
        $product = $this->product->getProductDetail($id);
        $images  = $this->imageModel->findByProductId($id);

        $view  = 'product/show';
        $title = "Chi tiết sản phẩm #$id";

        require_once PATH_VIEW_ADMIN_MAIN;
    }

public function delete()
{
    $id = (int)($_GET['id'] ?? 0);

    if (!$id) {
        $_SESSION['success'] = false;
        $_SESSION['msg']     = 'Thiếu ID sản phẩm!';
    } else {
        // Gọi method mới để xóa cả product, variants và images trong transaction
        $ok = $this->product->deleteProductWithRelations($id);

        $_SESSION['success'] = $ok;
        $_SESSION['msg']     = $ok
            ? 'Xóa sản phẩm thành công!'
            : 'Xóa thất bại! Vui lòng thử lại hoặc kiểm tra ràng buộc.';
    }

    header('Location: ' . BASE_URL_ADMIN . '&action=product-index');
    exit;
}

}
