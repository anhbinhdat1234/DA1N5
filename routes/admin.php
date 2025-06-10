<?php
$action = $_GET['action'] ?? '/';

// Danh sách tất cả action của admin
$adminActions = [
    '/',
    'show-form-login',
    'login',
    'logout',

    // User
    'users-index',
    'users-show',
    'users-create',
    'users-store',
    'users-edit',
    'users-update',
    'users-delete',

    // Product
    'product-index',
    'product-show',
    'product-create',
    'product-store',
    'product-edit',
    'product-update',
    'product-delete',

    // Order (Đơn hàng)
    'orders-index',
    'orders-show',
    'orders-delete',
    'orders-update-status'
];

// Nếu chưa đăng nhập admin thì chỉ cho phép truy cập login
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    if (!in_array($action, ['show-form-login', 'login'])) {
        header('Location: ' . BASE_URL_ADMIN . '&action=show-form-login');
        exit();
    }
}

// Require các controller chính
require_once PATH_VIEW_ADMIN . 'layout/header.php';
require_once PATH_CONTROLLER_ADMIN . 'DashboardController.php';
require_once PATH_CONTROLLER_ADMIN . 'AuthenController.php';
require_once PATH_CONTROLLER_ADMIN . 'UserController.php';
require_once PATH_CONTROLLER_ADMIN . 'ProductController.php';
require_once PATH_CONTROLLER_ADMIN . 'OrderController.php';

// Routing admin actions
match ($action) {
    // Dashboard
    '/' => (new DashboardController())->index(),

    // Auth
    'show-form-login' => (new AuthenController())->showFormLogin(),
    'login'           => (new AuthenController())->login(),
    'logout'          => (new AuthenController())->logout(),

    // User
    'users-index'  => (new UserController())->index(),
    'users-show'   => (new UserController())->show(),
    'users-create' => (new UserController())->create(),
    'users-store'  => (new UserController())->store(),
    'users-edit'   => (new UserController())->edit(),
    'users-update' => (new UserController())->update(),
    'users-delete' => (new UserController())->delete(),

    // Product
    'product-index'  => (new ProductController())->index(),
    'product-show'   => (new ProductController())->show(),
    'product-create' => (new ProductController())->create(),
    'product-store'  => (new ProductController())->store(),
    'product-edit'   => (new ProductController())->edit(),
    'product-update' => (new ProductController())->update(),
    'product-delete' => (new ProductController())->delete(),

    // Đơn hàng (Order)
    'orders-index'         => (new OrderController())->index(),
    'orders-show'          => (new OrderController())->show(),
    'orders-delete'        => (new OrderController())->delete(),
    'orders-update-status' => (new OrderController())->updateStatus(),

    default => (new DashboardController())->index(),
};
