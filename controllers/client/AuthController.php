<?php
// app/controllers/AuthController.php

require_once PATH_MODEL . 'User.php';
require_once PATH_MODEL . 'Order.php';
require_once PATH_MODEL . 'OrderItem.php';

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
                // Thiết lập session
                $_SESSION['user'] = [
                    'id'      => $user['id'],
                    'name'    => $user['name'],
                    'phone'   => $user['phone']   ?? '',
                    'address' => $user['address'] ?? ''
                ];
                $_SESSION['flash'] = 'Đăng nhập thành công!';

                // Redirect về đúng nơi đã yêu cầu login
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
                $_SESSION['flash'] = 'Đăng ký thành công! Hãy đăng nhập.';
                header('Location: ' . BASE_URL . '?action=login_form');
                exit;
            }
        }

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
        $_SESSION['flash'] = 'Bạn đã đăng xuất.';
        header('Location: ' . BASE_URL . '?action=login_form');
        exit;
    }

    // Hiển thị và xử lý Profile + lịch sử đơn hàng
 public function profile()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user']['id'])) {
            header('Location: ' . BASE_URL . '?action=login_form');
            exit;
        }

        $userId    = $_SESSION['user']['id'];
        $userModel = new User();

        // POST cập nhật profile
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name    = trim($_POST['name']    ?? '');
            $phone   = trim($_POST['phone']   ?? '');
            $address = trim($_POST['address'] ?? '');

            $userModel->updateProfile($userId, [
                'name'    => $name,
                'phone'   => $phone,
                'address' => $address,
            ]);

            $_SESSION['user']['name']    = $name;
            $_SESSION['user']['phone']   = $phone;
            $_SESSION['user']['address'] = $address;

            header('Location: ' . BASE_URL . '?action=profile&updated=1');
            exit;
        }

        // Lấy user mới nhất
        $userData = $userModel->findById($userId);

        // Lấy orders kèm coupon & discount
        $orderModel = new Order();
        $orders     = $orderModel->getOrdersByUser($userId);

        // Nếu có POST sửa địa chỉ order
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_type'])) {
            if ($_POST['action_type'] === 'update_address') {
                $orderModel->updateAddress((int)$_POST['order_id'], trim($_POST['new_address']));
                header('Location: ' . BASE_URL . '?action=profile');
                exit;
            }
            if ($_POST['action_type'] === 'cancel_order') {
                $orderModel->cancel((int)$_POST['order_id']);
                header('Location: ' . BASE_URL . '?action=profile');
                exit;
            }
        }

        // Lấy chi tiết items
        $orderItemModel  = new OrderItem();
        $ordersWithItems = [];
        foreach ($orders as $order) {
            $items = $orderItemModel->getItemsByOrder($order['id']);
            $ordersWithItems[] = [
                'order' => $order,
                'items' => $items,
            ];
        }

        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'auth/profile.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php';
    }
    public function updateOrderAddress()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId  = $_SESSION['user']['id'] ?? null;
        $orderId = (int)($_POST['order_id'] ?? 0);
        $newAddr = trim($_POST['new_address'] ?? '');
        if ($userId && $orderId && $newAddr !== '') {
            (new Order())->updateAddress($orderId, $newAddr);
        }
        header('Location: ' . BASE_URL . '?action=profile');
        exit;
    }

    /**
     * Xử lý POST từ form cancel_order
     */
    public function cancelOrder()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId  = $_SESSION['user']['id'] ?? null;
        $orderId = (int)($_POST['order_id'] ?? 0);
        if ($userId && $orderId) {
            (new Order())->cancel($orderId);
        }
        header('Location: ' . BASE_URL . '?action=profile');
        exit;
    }
}
