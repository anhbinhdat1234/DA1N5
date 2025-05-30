<?php

class BookController
{
    private $book;
    private $category;

    public function __construct()
    {
        $this->book = new Book();
        $this->category = new Category();
    }

    // Hiển thị danh sách
    public function index()
    {
        $view = 'books/index';
        $title = 'Danh sách Book';
        $data = $this->book->getAll();
        
        require_once PATH_VIEW_ADMIN_MAIN;
    }

    // Hiển thị chi tiết theo ID
    public function show()
    {
        try {
            if (!isset($_GET['id'])) {
                throw new Exception('Thiếu tham số "id"', 99);
            }

            $id = $_GET['id'];

            $book = $this->book->getByID($id);

            if (empty($book)) {
                throw new Exception("Book có ID = $id KHÔNG TỒN TẠI!");
            }

            $view = 'books/show';
            $title = "Chi tiết Book có ID = $id";

            require_once PATH_VIEW_ADMIN_MAIN;
        } catch (\Throwable $th) {
            $_SESSION['success'] = false;
            $_SESSION['msg'] = $th->getMessage();

            header('Location: ' . BASE_URL_ADMIN . '&action=books-index');
            exit();
        }
    }

    // Hiển thị form thêm mới
    public function create()
    {
        $view = 'books/create';
        $title = 'Thêm mới Book';

        $categories = $this->category->select();
        $categoryPluck = array_column($categories, 'name', 'id');

        require_once PATH_VIEW_ADMIN_MAIN;
    }

    // Lưu dữ liệu thêm mới
    public function store()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                throw new Exception('Yêu cầu phương thức phải là POST');
            }

            $data = $_POST + $_FILES;

            if ($data['img_cover']['size'] > 0) {
                $data['img_cover'] = upload_file('books', $data['img_cover']);
            } else {
                $data['img_cover'] = null;
            }
            
            $rowCount = $this->book->insert($data);

            if ($rowCount > 0) {
                $_SESSION['success'] = true;
                $_SESSION['msg'] = 'Thao tác thành công!';
            } else {
                throw new Exception('Thao tác KHÔNG thành công!');
            }
        } catch (\Throwable $th) {
            $_SESSION['success'] = false;
            $_SESSION['msg'] = $th->getMessage();
        }

        header('Location: ' . BASE_URL_ADMIN . '&action=books-create');
        exit();
    }

    // Hiển thị form cập nhật theo ID
    public function edit()
    {
        try {
            if (!isset($_GET['id'])) {
                throw new Exception('Thiếu tham số "id"', 99);
            }

            $id = $_GET['id'];

            $book = $this->book->getByID($id);

            if (empty($book)) {
                throw new Exception("Book có ID = $id KHÔNG TỒN TẠI!");
            }

            $view = 'books/edit';
            $title = "Cập nhật Book có ID = $id";

            $categories = $this->category->select();
            $categoryPluck = array_column($categories, 'name', 'id');

            require_once PATH_VIEW_ADMIN_MAIN;
        } catch (\Throwable $th) {
            $_SESSION['success'] = false;
            $_SESSION['msg'] = $th->getMessage();

            header('Location: ' . BASE_URL_ADMIN . '&action=books-index');
            exit();
        }
    }

    // Lưu dữ liệu cập nhật theo ID
    public function update()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                throw new Exception('Yêu cầu phương thức phải là POST');
            }

            if (!isset($_GET['id'])) {
                throw new Exception('Thiếu tham số "id"', 99);
            }

            $id = $_GET['id'];

            $book = $this->book->find('*', 'id = :id', ['id' => $id]);

            if (empty($book)) {
                throw new Exception("Book có ID = $id KHÔNG TỒN TẠI!");
            }

            $data = $_POST + $_FILES;

            if ($data['img_cover']['size'] > 0) {
                $data['img_cover'] = upload_file('books', $data['img_cover']);
            } else {
                $data['img_cover'] = $book['img_cover'];
            }

            $data['updated_at'] = date('Y-m-d H:i:s');

            $rowCount = $this->book->update($data, 'id = :id', ['id' => $id]);

            if ($rowCount > 0) {

                if (
                    $_FILES['img_cover']['size'] > 0
                    && !empty($book['img_cover'])
                    && file_exists(PATH_ASSETS_UPLOADS . $book['img_cover'])
                ) {
                    unlink(PATH_ASSETS_UPLOADS . $book['img_cover']);
                }

                $_SESSION['success'] = true;
                $_SESSION['msg'] = 'Thao tác thành công!';
            } else {
                throw new Exception('Thao tác KHÔNG thành công!');
            }
        } catch (\Throwable $th) {
            $_SESSION['success'] = false;
            $_SESSION['msg'] = $th->getMessage() . ' - Line: ' . $th->getLine();

            if ($th->getCode() == 99) {
                header('Location: ' . BASE_URL_ADMIN . '&action=books-index');
                exit();
            }
        }

        header('Location: ' . BASE_URL_ADMIN . '&action=books-edit&id=' . $id);
        exit();
    }

    // Xóa dữ liệu theo ID
    public function delete()
    {
        try {
            if (!isset($_GET['id'])) {
                throw new Exception('Thiếu tham số "id"', 99);
            }

            $id = $_GET['id'];

            $book = $this->book->find('*', 'id = :id', ['id' => $id]);

            if (empty($book)) {
                throw new Exception("Book có ID = $id KHÔNG TỒN TẠI!");
            }

            $rowCount = $this->book->delete('id = :id', ['id' => $id]);

            if ($rowCount > 0) {

                if (!empty($book['img_cover']) && file_exists(PATH_ASSETS_UPLOADS . $book['img_cover'])) {
                    unlink(PATH_ASSETS_UPLOADS . $book['img_cover']);
                }

                $_SESSION['success'] = true;
                $_SESSION['msg'] = 'Thao tác thành công!';
            } else {
                throw new Exception('Thao tác KHÔNG thành công!');
            }
        } catch (\Throwable $th) {
            $_SESSION['success'] = false;
            $_SESSION['msg'] = $th->getMessage();
        }

        header('Location: ' . BASE_URL_ADMIN . '&action=books-index');
        exit();
    }
}
