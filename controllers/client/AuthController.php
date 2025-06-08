<?php
// app/controllers/AuthController.php
class AuthController
{
    // Hiển thị form Login
    public function showLoginForm()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        // nếu có flash từ register thì giữ lại $flash để view dùng
        $flash = $_SESSION['flash'] ?? '';
        unset($_SESSION['flash']);

        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'auth/login.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php';
    }

    // Xử lý Login
    public function login()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL);
            exit;
        }

        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');
        $error    = '';

        if ($email === '' || $password === '') {
            $error = 'Vui lòng nhập đầy đủ email và mật khẩu';
        } else {
            $userModel = new User();
            $user = $userModel->find('*', "email = '" . addslashes($email) . "'");
            if ($user && password_verify($password, $user['password'])) {
                // gán session chuẩn
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role']; 
                $_SESSION['user']      = [
                    'id'   => $user['id'],
                    'name' => $user['name'],
                ];
                // flash success
                $_SESSION['flash'] = 'Đăng nhập thành công!';
                // redirect sau login
                if (isset($_SESSION['redirect_after_login'])) {
                    $url = $_SESSION['redirect_after_login'];
                    unset($_SESSION['redirect_after_login']);
                    header('Location: ' . $url);
                } else {
                    header('Location: ' . BASE_URL);
                }
                exit;
            } else {
                $error = 'Email hoặc mật khẩu không đúng';
            }
        }

        // Nếu login thất bại, render lại form có $error
        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'auth/login.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php';
    }

    // Hiển thị form Register
    public function showRegisterForm()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL);
            exit;
        }
        // flash có thể đến từ register thất bại hoặc trước đó
        $flash = $_SESSION['flash'] ?? '';
        unset($_SESSION['flash']);

        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'auth/register.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php';
    }

    // Xử lý đăng ký
    public function register()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL);
            exit;
        }

        $name     = trim($_POST['name']     ?? '');
        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');
        $error    = '';

        if ($name === '' || $email === '' || $password === '') {
            $error = 'Vui lòng điền đầy đủ họ tên, email và mật khẩu';
        } else {
            $userModel = new User();
            $existing = $userModel->find('*', "email = '" . addslashes($email) . "'");
            if ($existing) {
                $error = 'Email này đã được sử dụng';
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $userModel->create([
                    'name'     => $name,
                    'email'    => $email,
                    'password' => $hashed,
                ]);
                // flash success
                $_SESSION['flash'] = 'Đăng ký thành công! Hãy đăng nhập.';
                header('Location: ' . BASE_URL . '?action=login_form');
                exit;
            }
        }

        // Nếu register thất bại, render lại form có $error
        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'auth/register.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php';
    }

    // Logout
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_unset();
        session_destroy();
        session_start();
        // flash sau logout
        $_SESSION['flash'] = 'Bạn đã đăng xuất.';
        header('Location: ' . BASE_URL . '?action=login_form');
        exit;
    }
    //Profile
    public function profile()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['user']['id'])) {
            header('Location: ' . BASE_URL . '?action=login_form');
            exit;
        }

        $userId    = $_SESSION['user']['id'];
        $userModel = new User();
        $userData  = $userModel->find('*', "id = $userId");

        // Lấy danh sách orders của user
        $orderModel     = new Order();
        $orders         = $orderModel->getOrdersByUser($userId);

        // Lấy chi tiết từng order
        $orderItemModel = new OrderItem();
        $ordersWithItems = [];
        foreach ($orders as $order) {
            $items = $orderItemModel->getItemsByOrder($order['id']);
            $ordersWithItems[] = ['order' => $order, 'items' => $items];
        }

        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'auth/profile.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php';
    }
}
