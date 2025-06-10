<?php

class UserController
{
    private $user;

    public function __construct()
    {
        $this->user = new User();
    }

    // Danh sách user
    public function index()
    {
        $view = 'users/index';
        $title = 'Danh sách User';
        $users = $this->user->getAll();
        require_once PATH_VIEW_ADMIN_MAIN;
    }

    // Thêm mới user (hiện form)
    public function create()
    {
        $view = 'users/create';
        $title = 'Thêm mới User';
        require_once PATH_VIEW_ADMIN_MAIN;
    }

    // Lưu user mới
    public function store()
    {
        try {
            $data = $_POST;
            $_SESSION['errors'] = [];

            // Validate
            if (empty($data['name']) || strlen($data['name']) > 50) {
                $_SESSION['errors']['name'] = 'Tên bắt buộc, tối đa 50 ký tự.';
            }
            if (
                empty($data['email']) || strlen($data['email']) > 100 ||
                !filter_var($data['email'], FILTER_VALIDATE_EMAIL) ||
                $this->user->findByEmail($data['email'])
            ) {
                $_SESSION['errors']['email'] = 'Email hợp lệ, không được trùng.';
            }
            if (empty($data['password']) || strlen($data['password']) < 6 || strlen($data['password']) > 30) {
                $_SESSION['errors']['password'] = 'Mật khẩu từ 6-30 ký tự.';
            }
            if (!empty($_SESSION['errors'])) {
                throw new Exception('Dữ liệu lỗi!');
            }

            // Hash mật khẩu
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['role'] = $data['role'] ?? 'user';

            $this->user->create($data);
            $_SESSION['success'] = true;
            $_SESSION['msg'] = 'Tạo user thành công!';
        } catch (Exception $e) {
            $_SESSION['success'] = false;
            $_SESSION['msg'] = $e->getMessage();
        }

        header('Location: ' . BASE_URL_ADMIN . '&action=users-index');
        exit();
    }
    public function show()
    {
        $id = $_GET['id'] ?? null;
        $user = $this->user->findById($id);
        if (!is_array($user)) {
            $_SESSION['success'] = false;
            $_SESSION['msg'] = 'Không tìm thấy user!';
            header('Location: ' . BASE_URL_ADMIN . '&action=users-index');
            exit();
        }
        $view = 'users/show';
        $title = 'Chi tiết User';
        require_once PATH_VIEW_ADMIN_MAIN;
    }



    // Form sửa user
    public function edit()
    {
        $id = $_GET['id'] ?? null;
        $user = $this->user->findById($id);
        if (!$user) {
            $_SESSION['success'] = false;
            $_SESSION['msg'] = 'Không tìm thấy user!';
            header('Location: ' . BASE_URL_ADMIN . '&action=users-index');
            exit();
        }
        $view = 'users/edit';
        $title = 'Cập nhật User';
        require_once PATH_VIEW_ADMIN_MAIN;
    }

    // Cập nhật user
    public function update()
    {
        $id = $_GET['id'] ?? null;
        $user = $this->user->findById($id);
        try {
            $data = $_POST;
            $_SESSION['errors'] = [];

            // Validate
            if (empty($data['name']) || strlen($data['name']) > 50) {
                $_SESSION['errors']['name'] = 'Tên bắt buộc, tối đa 50 ký tự.';
            }
            $other = $this->user->findByEmail($data['email']);
            if (
                empty($data['email']) || strlen($data['email']) > 100 ||
                !filter_var($data['email'], FILTER_VALIDATE_EMAIL) ||
                ($other && $other['id'] != $id)
            ) {
                $_SESSION['errors']['email'] = 'Email hợp lệ, không được trùng.';
            }

            // Nếu nhập mật khẩu mới thì kiểm tra & hash lại
            if (!empty($data['password'])) {
                if (strlen($data['password']) < 6 || strlen($data['password']) > 30) {
                    $_SESSION['errors']['password'] = 'Mật khẩu từ 6-30 ký tự.';
                } else {
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                }
            } else {
                unset($data['password']); // Không cập nhật trường này nếu không nhập mới
            }

            if (!empty($_SESSION['errors'])) {
                throw new Exception('Dữ liệu lỗi!');
            }

            $data['updated_at'] = date('Y-m-d H:i:s');
            $data['role'] = $data['role'] ?? $user['role'];
            $this->user->updateUser($id, $data);

            $_SESSION['success'] = true;
            $_SESSION['msg'] = 'Cập nhật user thành công!';
        } catch (Exception $e) {
            $_SESSION['success'] = false;
            $_SESSION['msg'] = $e->getMessage();
        }
        header('Location: ' . BASE_URL_ADMIN . '&action=users-index' );
        exit();
    }


    // Xóa user
    public function delete()
    {
        $id = $_GET['id'] ?? null;
        $this->user->deleteUser($id);
        $_SESSION['success'] = true;
        $_SESSION['msg'] = 'Xóa user thành công!';
        header('Location: ' . BASE_URL_ADMIN . '&action=users-index');
        exit();
    }
}
