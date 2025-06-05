<?php
$action = $_GET['action'] ?? '/';

match ($action) {
    '/' => (new HomeController)->index(),
    'login_form'    => (new AuthController())->showLoginForm(),
    'login'         => (new AuthController())->login(),
    'register_form' => (new AuthController())->showRegisterForm(),
    'register'      => (new AuthController())->register(),
    'logout'        => (new AuthController())->logout(),
    'test-show' => (new TestController)->show(),
    'product_ct' => (new ProductController)->detail(),
    'category' => (new CategoryController)->index(),
};
