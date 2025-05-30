<?php

class UserController
{
    private $user;

    public function __construct()
    {
        $this->user = new User();
    }

    // Hiển thị danh sách
    public function index()
    {
        $view = 'users/index';
        $title = 'Danh sách User';
        $data = $this->user->select('*', '1 = 1 ORDER BY id DESC');

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

            $user = $this->user->find('*', 'id = :id', ['id' => $id]);

            if (empty($user)) {
                throw new Exception("User có ID = $id KHÔNG TỒN TẠI!");
            }

            $view = 'users/show';
            $title = "Chi tiết User có ID = $id";

            require_once PATH_VIEW_ADMIN_MAIN;
        } catch (\Throwable $th) {
            $_SESSION['success'] = false;
            $_SESSION['msg'] = $th->getMessage();

            header('Location: ' . BASE_URL_ADMIN . '&action=users-index');
            exit();
        }
    }

    // Hiển thị form thêm mới
    public function create()
    {
        $view = 'users/create';
        $title = 'Thêm mới User';

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

            $_SESSION['errors'] = [];

            // Validate dữ liệu
            if (empty($data['name']) || strlen($data['name']) > 50) {
                $_SESSION['errors']['name'] = 'Trường name bắt buộc và độ dài không quá 50 ký tự.';
            }

            if (
                empty($data['email'])
                || strlen($data['email']) > 100

                || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)

                || !empty( $this->user->find('*', 'email = :email', [ 'email' => $data['email'] ]) )
            ) {
                $_SESSION['errors']['email'] = 'Trường email bắt buộc, độ dài không quá 100 ký tự và không được trùng';
            }

            if (empty($data['password']) || strlen($data['password']) < 6 || strlen($data['password']) > 30) {
                $_SESSION['errors']['password'] = 'Trường password bắt buộc, độ dài trong khoảng từ 6 đến 30 ký tự.';
            }
            
            if ($data['avatar']['size'] > 0) {

                if ($data['avatar']['size'] > 2 * 1024 * 1024) {
                    $_SESSION['errors']['avatar_size'] = 'Trường avatar có dung lượng tối đa 2MB';
                }
                
                $fileType = $data['avatar']['type'];
                $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($fileType, $allowedTypes)) {
                    $_SESSION['errors']['avatar_type'] = 'Xin lỗi, chỉ chấp nhận các loại file JPG, JPEG, PNG, GIF.';
                }
            }
            
            if (!empty($_SESSION['errors'])) {

                $_SESSION['data'] = $data;

                throw new Exception('Dữ liệu lỗi!');
            }

            if ($data['avatar']['size'] > 0) {
                $data['avatar'] = upload_file('users', $data['avatar']);
            } else {
                $data['avatar'] = null;
            }
            
            $rowCount = $this->user->insert($data);

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

        header('Location: ' . BASE_URL_ADMIN . '&action=users-create');
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

            $user = $this->user->find('*', 'id = :id', ['id' => $id]);

            if (empty($user)) {
                throw new Exception("User có ID = $id KHÔNG TỒN TẠI!");
            }

            $view = 'users/edit';
            $title = "Cập nhật User có ID = $id";

            require_once PATH_VIEW_ADMIN_MAIN;
        } catch (\Throwable $th) {
            $_SESSION['success'] = false;
            $_SESSION['msg'] = $th->getMessage();

            header('Location: ' . BASE_URL_ADMIN . '&action=users-index');
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

            $user = $this->user->find('*', 'id = :id', ['id' => $id]);

            if (empty($user)) {
                throw new Exception("User có ID = $id KHÔNG TỒN TẠI!");
            }

            $data = $_POST + $_FILES;

            $_SESSION['errors'] = [];

            // Validate dữ liệu
            if (empty($data['name']) || strlen($data['name']) > 50) {
                $_SESSION['errors']['name'] = 'Trường name bắt buộc và độ dài không quá 50 ký tự.';
            }

            if (
                empty($data['email'])
                || strlen($data['email']) > 100
                || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)
                || !empty($this->user->find('*', 'email = :email AND id != :id', ['email' => $data['email'], 'id' => $id]))
            ) {
                $_SESSION['errors']['email'] = 'Trường email bắt buộc, độ dài không quá 100 ký tự và không được trùng';
            }

            if (empty($data['password']) || strlen($data['password']) < 6 || strlen($data['password']) > 30) {
                $_SESSION['errors']['password'] = 'Trường password bắt buộc, độ dài trong khoảng từ 6 đến 30 ký tự.';
            }

            if ($data['avatar']['size'] > 0) {

                if ($data['avatar']['size'] > 2 * 1024 * 1024) {
                    $_SESSION['errors']['avatar_size'] = 'Trường avatar có dung lượng tối đa 2MB';
                }

                $fileType = $data['avatar']['type'];
                $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($fileType, $allowedTypes)) {
                    $_SESSION['errors']['avatar_type'] = 'Xin lỗi, chỉ chấp nhận các loại file JPG, JPEG, PNG, GIF.';
                }
            }

            if (!empty($_SESSION['errors'])) {
                throw new Exception('Dữ liệu lỗi!');
            }

            if ($data['avatar']['size'] > 0) {
                $data['avatar'] = upload_file('users', $data['avatar']);
            } else {
                $data['avatar'] = $user['avatar'];
            }

            $data['updated_at'] = date('Y-m-d H:i:s');

            $rowCount = $this->user->update($data, 'id = :id', ['id' => $id]);

            if ($rowCount > 0) {

                if (
                    $_FILES['avatar']['size'] > 0
                    && !empty($user['avatar'])
                    && file_exists(PATH_ASSETS_UPLOADS . $user['avatar'])
                ) {
                    unlink(PATH_ASSETS_UPLOADS . $user['avatar']);
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
                header('Location: ' . BASE_URL_ADMIN . '&action=users-index');
                exit();
            }
        }

        header('Location: ' . BASE_URL_ADMIN . '&action=users-edit&id=' . $id);
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

            $user = $this->user->find('*', 'id = :id', ['id' => $id]);

            if (empty($user)) {
                throw new Exception("User có ID = $id KHÔNG TỒN TẠI!");
            }

            $rowCount = $this->user->delete('id = :id', ['id' => $id]);

            if ($rowCount > 0) {

                if (!empty($user['avatar']) && file_exists(PATH_ASSETS_UPLOADS . $user['avatar'])) {
                    unlink(PATH_ASSETS_UPLOADS . $user['avatar']);
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

        header('Location: ' . BASE_URL_ADMIN . '&action=users-index');
        exit();
    }
}
