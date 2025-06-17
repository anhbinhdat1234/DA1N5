<?php
$action = $_GET['action'] ?? '/';

match ($action) {
    '/' => (new HomeController)->index(),

    //Login, Register, Logout
    'login_form'    => (new AuthController())->showLoginForm(),
    'login'         => (new AuthController())->login(),
    'register_form' => (new AuthController())->showRegisterForm(),
    'register'      => (new AuthController())->register(),
    'logout'        => (new AuthController())->logout(),

    //User Profile
    'profile' => (new AuthController())->profile(),
    'update_order_address' => (new AuthController())->updateOrderAddress(),
    'cancel_order'         => (new AuthController())->cancelOrder(),

    //Detail product
    'product'         => (new ProductController())->index(),
    'product_detail', 'product_ct'  => (new ProductController())->detail(),

    //Category
    'category' => (new CategoryController)->index(),

    //Search
    'search'   => (new SearchController())->search(),

    //Cart
    'view_cart'       => (new CartController())->index(),
    'add_to_cart'     => (new CartController())->add(),
    'update_cart'     => (new CartController())->update(),
    'clear_cart'      => (new CartController())->clear(),
    'apply_coupon'    => (new CartController())->applyCoupon(),
    'remove_coupon'   => (new CartController())->removeCoupon(),

    //Checkout
    'checkout_form'   => (new CheckoutController())->index(),
    'submit_checkout' => (new CheckoutController())->submit(),
    'thank_you'       => (new CheckoutController())->thankYou(),

    // 👉 Thêm xử lý cho action 'create'
    'create' => (new SliderController())->create(), // hoặc controller tương ứng

    // 👉 Default để tránh lỗi khi không khớp action nào
    default => (new HomeController())->index(),
};
