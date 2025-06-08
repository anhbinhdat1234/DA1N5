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

    // Xử lý POST đặt hàng
    public function submit()
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

        // Lấy dữ liệu
        $address = trim($_POST['address'] ?? '');
        $phone   = trim($_POST['phone']   ?? '');

        // Validate
        $errors = [];
        if ($address === '') $errors[] = 'Vui lòng nhập địa chỉ giao hàng.';
        if ($phone === '')   $errors[] = 'Vui lòng nhập số điện thoại.';
        if ($errors) {
            $_SESSION['checkout_errors'] = $errors;
            header('Location: ' . BASE_URL . '?mod=client&action=checkout_form');
            exit;
        }

        $totalAmount = array_sum(array_column($cartItems, 'subtotal'));
        $orderModel     = new Order();
        $orderItemModel = new OrderItem();
        $shippingModel  = new Shipping();

        try {
            // 1) tạo order
            $orderId = $orderModel->createOrder($userId, $totalAmount);

            // 2) lưu địa chỉ + phone
            $shippingModel->createShipping($orderId, $address, $phone);

            // 3) lưu chi tiết
            $itemsToInsert = [];
            foreach ($cartItems as $it) {
                $itemsToInsert[] = [
                    'product_variant_id' => $it['product_variant_id'],
                    'quantity'           => $it['quantity'],
                    'price'              => $it['price'],
                ];
            }
            $orderItemModel->createItems($orderId, $itemsToInsert);

            // 4) xóa cart
            (new Cart())->clearCart($userId);

            header('Location: ' . BASE_URL . '?mod=client&action=thank_you&order_id=' . $orderId);
            exit;
        } catch (\Exception $e) {
            $_SESSION['checkout_errors'] = ['Có lỗi khi tạo đơn hàng. Vui lòng thử lại.'];
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
