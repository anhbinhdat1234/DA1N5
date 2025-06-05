<?php

class AuthController
{
    public function showLoginForm()
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'auth/login.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php';
    }

    public function login()
    {
        if (isset($_SESSION['user_id'])) {
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
            $user = $userModel->find('*', "email = '" . addslashes($email) . "'");

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                header('Location: ' . BASE_URL);
                exit;
            } else {
                $error = 'Email hoặc mật khẩu không đúng';
            }
        }

        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'auth/login.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php';
    }

    public function showRegisterForm()
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL);
            exit;
        }

        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'auth/register.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php';
    }

    public function register()
    {
        if (isset($_SESSION['user_id'])) {
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

                header('Location: ' . BASE_URL . '?action=login_form');
                exit;
            }
        }

        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'auth/register.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php';
    }

    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . '?action=login_form');
        exit;
    }
}
