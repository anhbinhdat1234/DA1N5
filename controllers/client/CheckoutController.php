<?php
// controllers/client/CheckoutController.php

require_once __DIR__ . '/../../models/Order.php';
require_once __DIR__ . '/../../models/OrderItem.php';
require_once __DIR__ . '/../../models/Shipping.php';
require_once __DIR__ . '/../../models/Cart.php';

class CheckoutController
{
    // Hiển thị form thanh toán
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (empty($_SESSION['user']['id'])) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header('Location: ' . BASE_URL . '?action=login_form');
            exit;
        }

        $userId    = $_SESSION['user']['id'];
        $cartItems = (new Cart())->getCartItems($userId);
        if (empty($cartItems)) {
            header('Location: ' . BASE_URL . '?mod=client&action=view_cart');
            exit;
        }

        $total  = array_sum(array_column($cartItems, 'subtotal'));
        $errors = $_SESSION['checkout_errors'] ?? [];
        unset($_SESSION['checkout_errors']);

        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'checkout.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php';
    }
    //Giam gia
    public function form()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userId    = $_SESSION['user']['id'] ?? null;
        $cartModel = new Cart();
        $cartItems = $cartModel->getCartItems($userId);

        // Tính tổng trước giảm giá
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['subtotal'];
        }

        // Lấy discount từ session (nếu có)
        $discount     = $_SESSION['discount'] ?? 0;
        // Tính lại final total
        $finalTotal   = max(0, $total - $discount);

        // Đẩy vào view
        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'checkout.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php';
    }

    // Xử lý POST đặt hàng
public function submit()
{
    if (session_status() === PHP_SESSION_NONE) session_start();

    // 1) Đảm bảo đã login
    if (empty($_SESSION['user']['id'])) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: ' . BASE_URL . '?action=login_form');
        exit;
    }

    $userId    = $_SESSION['user']['id'];
    $cartItems = (new Cart())->getCartItems($userId);
    if (empty($cartItems)) {
        header('Location: ' . BASE_URL . '?mod=client&action=view_cart');
        exit;
    }

    // 2) Lấy địa chỉ & phone
    $address = trim($_POST['address'] ?? '');
    $phone   = trim($_POST['phone']   ?? '');

    // 3) Validate
    $errors = [];
    if ($address === '') $errors[] = 'Vui lòng nhập địa chỉ.';
    if ($phone   === '') $errors[] = 'Vui lòng nhập số điện thoại.';
    if ($errors) {
        $_SESSION['checkout_errors'] = $errors;
        header('Location: ' . BASE_URL . '?mod=client&action=checkout_form');
        exit;
    }

    // 4) Tính tổng gốc
    $totalAmount = array_sum(array_column($cartItems, 'subtotal'));

    // 5) Lấy mã và discount từ session
    $couponCode     = $_SESSION['coupon']['code'] ?? null;
    $discountAmount = $_SESSION['discount'] ?? 0;

    // 6) Tính total sau giảm
    $finalAmount = max(0, $totalAmount - $discountAmount);

    // 7) Tạo order
    $orderModel     = new Order();
    $orderItemModel = new OrderItem();
    $shippingModel  = new Shipping();

    try {
        $orderId = $orderModel->createOrder(
            $userId,
            $finalAmount,    // lưu tổng đã trừ
            $couponCode,     // lưu mã
            $discountAmount  // lưu số tiền giảm
        );
        // 8) Lưu shipping
        $shippingModel->createShipping($orderId, $address, $phone);
        // 9) Lưu chi tiết
        $toInsert = [];
        foreach ($cartItems as $it) {
            $toInsert[] = [
                'product_variant_id' => $it['product_variant_id'],
                'quantity'           => $it['quantity'],
                'price'              => $it['price'],
            ];
        }
        $orderItemModel->createItems($orderId, $toInsert);
        // 10) Xóa cart & coupon
        (new Cart())->clearCart($userId);
        unset($_SESSION['coupon'], $_SESSION['discount']);
        // 11) Redirect
        header('Location: ' . BASE_URL . '?mod=client&action=thank_you&order_id=' . $orderId);
        exit;

    } catch (\Exception $e) {
        $_SESSION['checkout_errors'] = ['Lỗi tạo đơn, thử lại.'];
        header('Location: ' . BASE_URL . '?mod=client&action=checkout_form');
        exit;
    }
}



    // Trang cảm ơn
    public function thankYou()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $orderId = (int)($_GET['order_id'] ?? 0);
        if (!$orderId) {
            header('Location: ' . BASE_URL);
            exit;
        }

        $orderModel     = new Order();
        $orderItemModel = new OrderItem();

        $order      = $orderModel->findOrderById($orderId);
        $orderItems = $orderItemModel->getItemsByOrder($orderId);

        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'thank_you.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php';
    }
}
