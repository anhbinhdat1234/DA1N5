<?php
class AuthController
{
    // Hiển thị form Login
    public function showLoginForm()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL);
            exit;
        }

        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'auth/login.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php';
    }

    // Xử lý Login
    public function login()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL);
            exit;
        }

        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $error    = '';

        if (empty($email) || empty($password)) {
            $error = 'Vui lòng nhập đầy đủ email và mật khẩu';
        } else {
            $userModel = new User();
            // Lưu ý: nên sửa hàm find() trong Model để binding param, tránh SQL injection
            $user = $userModel->find('*', "email = '" . addslashes($email) . "'");

            if ($user && password_verify($password, $user['password'])) {
                // _Lưu thông tin user vào session_
                $_SESSION['user'] = [
                    'id'    => $user['id'],
                    'name'  => $user['name'],
                    'email' => $user['email']
                ];
                header('Location: ' . BASE_URL);
                exit;
            } else {
                $error = 'Email hoặc mật khẩu không đúng';
            }
        }

        // Nếu login thất bại, render lại form và show error
        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'auth/login.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php';
    }

    // Hiển thị form Register
    public function showRegisterForm()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL);
            exit;
        }

        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'auth/register.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php';
    }

    // Xử lý đăng ký
    public function register()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL);
            exit;
        }

        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $error    = '';

        if (empty($name) || empty($email) || empty($password)) {
            $error = 'Vui lòng điền đầy đủ họ tên, email và mật khẩu';
        } else {
            $userModel = new User();
            $existing = $userModel->find('*', "email = '" . addslashes($email) . "'");
            if ($existing) {
                $error = 'Email này đã được sử dụng';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $data = [
                    'name'     => $name,
                    'email'    => $email,
                    'password' => $hashedPassword
                ];
                $userModel->create($data);

                // Sau khi đăng ký thành công, redirect về form login
                header('Location: ' . BASE_URL . '?action=login_form');
                exit;
            }
        }

        // Nếu register thất bại (thieu field hoặc trùng email), render lại form
        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'auth/register.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php';
    }

    // Logout
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Hủy session hoàn toàn
        $_SESSION = [];
        session_unset();
        session_destroy();

        header('Location: ' . BASE_URL . '?action=login_form');
        exit;
    }
}
