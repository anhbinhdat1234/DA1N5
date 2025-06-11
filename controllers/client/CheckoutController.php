<?php
// controllers/client/CheckoutController.php
require_once PATH_MODEL . 'Cart.php';
require_once PATH_MODEL . 'Order.php';
require_once PATH_MODEL . 'OrderItem.php';
require_once PATH_MODEL . 'Shipping.php';

class CheckoutController
{
    /**
     * Hiển thị form thanh toán
     */
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
            header('Location: ' . BASE_URL . '?action=view_cart');
            exit;
        }

        $total  = array_sum(array_column($cartItems, 'subtotal'));
        $errors = $_SESSION['checkout_errors'] ?? [];
        unset($_SESSION['checkout_errors']);

        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'checkout.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php';
    }

    /**
     * Xử lý POST đặt hàng
     */
    public function submit()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId    = $_SESSION['user']['id'] ?? null;
        if (!$userId) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header('Location: ' . BASE_URL . '?action=login_form');
            exit;
        }

        $cartItems = (new Cart())->getCartItems($userId);
        if (empty($cartItems)) {
            header('Location: ' . BASE_URL . '?action=view_cart');
            exit;
        }

        $address = trim($_POST['address'] ?? '');
        $phone   = trim($_POST['phone']   ?? '');
        $note    = trim($_POST['note']    ?? '');
        $errors  = [];

        // Validate address (tối thiểu 10 ký tự)
        if (strlen($address) < 10) {
            $errors[] = 'Địa chỉ giao hàng phải ít nhất 10 ký tự.';
        }
        // Validate phone (must be 10-11 digits)
        if (!preg_match('/^0[0-9]{9,10}$/', $phone)) {
            $errors[] = 'Số điện thoại không hợp lệ (bắt đầu 0, 10-11 chữ số).';
        }

        if ($errors) {
            $_SESSION['checkout_errors'] = $errors;
            header('Location: ' . BASE_URL . '?action=checkout_form');
            exit;
        }

        $totalAmount    = array_sum(array_column($cartItems, 'subtotal'));
        $couponCode     = $_SESSION['coupon']['code'] ?? null;
        $discountAmount = $_SESSION['discount'] ?? 0;
        $finalAmount    = max(0, $totalAmount - $discountAmount);

        $orderModel     = new Order();
        $orderItemModel = new OrderItem();
        $shippingModel  = new Shipping();

        try {
            // 1) Tạo order
            $orderId = $orderModel->createOrder(
                $userId,
                $finalAmount,
                $couponCode,
                $discountAmount
            );

            // 2) Lưu shipping (bao gồm ghi chú)
            $shippingModel->createShipping($orderId, $address, $phone, $note);

            // 3) Lưu chi tiết đơn hàng
            $orderItemModel->createItems(
                $orderId,
                array_map(fn($it) => [
                    'product_variant_id' => $it['product_variant_id'],
                    'quantity'           => $it['quantity'],
                    'price'              => $it['price'],
                ], $cartItems)
            );

            // 4) Giảm tồn kho
            $orderModel->reduceStock($cartItems);

            // 5) Xóa giỏ và coupon
            (new Cart())->clearCart($userId);
            unset($_SESSION['coupon'], $_SESSION['discount']);

            // 6) Chuyển tới trang cảm ơn
            header('Location: ' . BASE_URL . '?action=thank_you&order_id=' . $orderId);
            exit;
        } catch (\Exception $e) {
            $_SESSION['checkout_errors'] = ['Lỗi hệ thống: ' . $e->getMessage()];
            header('Location: ' . BASE_URL . '?action=checkout_form');
            exit;
        }
    }

    /**
     * Trang cảm ơn
     */
    public function thankYou()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $orderId = (int)($_GET['order_id'] ?? 0);
        if (!$orderId) {
            header('Location: ' . BASE_URL);
            exit;
        }

        $order      = (new Order())->findOrderById($orderId);
        $orderItems = (new OrderItem())->getItemsByOrder($orderId);

        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'thank_you.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php';
    }
}
