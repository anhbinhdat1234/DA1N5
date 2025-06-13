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

    // --- INDEX ---
    public function index()
    {
        $products = $this->product->getWithCategory();
        foreach ($products as &$p) {
            $imgs = $this->imageModel->findByProductId((int)$p['id']);
            // nếu có upload hoặc link thì image_url đã lưu đầy đủ; ngược lại dùng placeholder
            $p['image_url'] = $imgs[0]['image_url'] 
                ?? '/assets/client/assets/img/default.png';
        }
        unset($p);

        $view  = 'product/index';
        $title = 'Danh sách sản phẩm';
        require_once PATH_VIEW_ADMIN_MAIN;
    }

    // --- CREATE FORM ---
    public function create()
    {
        $categories = $this->category->select();
        $view       = 'product/create';
        $title      = 'Thêm sản phẩm mới';
        require_once PATH_VIEW_ADMIN_MAIN;
    }

    // --- STORE NEW PRODUCT ---
    public function store()
    {
        $data = $_POST;
        $_SESSION['errors'] = [];
        $_SESSION['old']    = $data;

        // 1) Validation
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
            // load lại form mà không redirect
            $view       = 'product/create';
            $title      = 'Thêm sản phẩm mới';
            $categories = $this->category->select();
            require_once PATH_VIEW_ADMIN_MAIN;
            return;
        }

        try {
            // 2) Chèn vào products
            $productData = [
                'name'        => $data['name'],
                'price'       => $data['price'],
                'description' => $data['description'] ?? null,
                'category_id' => $data['category_id'],
                'created_at'  => date('Y-m-d H:i:s'),
            ];
            $newId = $this->product->insertGetId($productData);

            // 3) Upload file ảnh
            $uploadDir = PATH_ASSETS_UPLOADS . 'products/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }
            if (!empty($_FILES['images'])) {
                $files = $_FILES['images'];
                for ($i = 0; $i < count($files['name']); $i++) {
                    if ($files['error'][$i] === UPLOAD_ERR_OK) {
                        $ext  = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                        $fn   = uniqid('prod_') . ".$ext";
                        $dest = $uploadDir . $fn;
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

            // 4) External URLs và data URIs
            if (!empty($data['external_images'])) {
                $lines = explode("\n", trim($data['external_images']));
                foreach ($lines as $line) {
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
        } catch (\Exception $e) {
            $_SESSION['success'] = false;
            $_SESSION['msg']     = 'Lỗi hệ thống: ' . $e->getMessage();
        }

        unset($_SESSION['errors'], $_SESSION['old']);
        header('Location: ' . BASE_URL_ADMIN . '&action=product-index');
        exit;
    }

    // --- EDIT FORM ---
    public function edit()
    {
        $id         = (int)($_GET['id'] ?? 0);
        $product    = $this->product->find('*','id=:id',['id'=>$id]);
        $categories = $this->category->select();
        $images     = $this->imageModel->findByProductId($id);

        $view  = 'product/edit';
        $title = "Sửa sản phẩm #$id";
        require_once PATH_VIEW_ADMIN_MAIN;
    }

    // --- UPDATE PRODUCT ---
    public function update()
    {
        $id   = (int)($_GET['id'] ?? 0);
        $data = $_POST;
        $_SESSION['errors'] = [];
        $_SESSION['old']    = $data;

        // Validation
        if (empty($data['name'])) {
            $_SESSION['errors']['name'] = 'Tên sản phẩm bắt buộc.';
        }
        if (empty($data['price']) || !is_numeric($data['price'])) {
            $_SESSION['errors']['price'] = 'Giá phải là số.';
        }

        if (!empty($_SESSION['errors'])) {
            // reload form
            $view       = 'product/edit';
            $title      = "Sửa sản phẩm #$id";
            $product    = $this->product->find('*','id=:id',['id'=>$id]);
            $categories = $this->category->select();
            $images     = $this->imageModel->findByProductId($id);
            require_once PATH_VIEW_ADMIN_MAIN;
            return;
        }

        try {
            // Update products
            $updateData = [
                'name'        => $data['name'],
                'price'       => $data['price'],
                'description' => $data['description'] ?? null,
                'category_id' => $data['category_id'],
            ];
            $this->product->update($updateData,'id=:id',['id'=>$id]);

            // Upload thêm file
            $uploadDir = PATH_ASSETS_UPLOADS . 'products/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }
            if (!empty($_FILES['images'])) {
                $files = $_FILES['images'];
                for ($i = 0; $i < count($files['name']); $i++) {
                    if ($files['error'][$i] === UPLOAD_ERR_OK) {
                        $ext  = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                        $fn   = uniqid('prod_') . ".$ext";
                        $dest = $uploadDir . $fn;
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

            // Thêm external/data-uri
            if (!empty($data['external_images'])) {
                $lines = explode("\n", trim($data['external_images']));
                foreach ($lines as $line) {
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
        } catch (\Exception $e) {
            $_SESSION['success'] = false;
            $_SESSION['msg']     = 'Lỗi hệ thống: ' . $e->getMessage();
        }

        unset($_SESSION['errors'], $_SESSION['old']);
        header('Location: ' . BASE_URL_ADMIN . '&action=product-index');
        exit;
    }

    // --- SHOW DETAIL ---
    public function show()
    {
        $id      = (int)($_GET['id'] ?? 0);
        $product = $this->product->getProductDetail($id);
        $images  = $this->imageModel->findByProductId($id);

        $view  = 'product/show';
        $title = "Chi tiết #$id";
        require_once PATH_VIEW_ADMIN_MAIN;
    }

    // --- DELETE WITH CASCADE LOGIC ---
    public function delete()
    {
        $id = (int)($_GET['id'] ?? 0);
        $ok = $this->product->deleteProductWithRelations($id);

        $_SESSION['success'] = $ok;
        $_SESSION['msg']     = $ok
            ? 'Xóa thành công!'
            : 'Xóa thất bại!';
        header('Location: ' . BASE_URL_ADMIN . '&action=product-index');
        exit;
    }
}
