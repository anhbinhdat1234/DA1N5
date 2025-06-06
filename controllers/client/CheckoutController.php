<?php
// controllers/client/CheckoutController.php

class CheckoutController
{
    /**
     * Hiển thị form thanh toán (checkout)
     * Nếu chưa login, redirect về login_form và lưu lại URL để redirect ngược về sau.
     */
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Nếu user chưa đăng nhập, lưu lại URL và chuyển sang trang login
        if (!isset($_SESSION['user']['id'])) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header('Location: ' . BASE_URL . '?action=login_form');
            exit;
        }

        $userId = $_SESSION['user']['id'];

        // Category (dùng để hiển thị menu chung như HomeController)
        $categoryModel = new Category();
        $categories = $categoryModel->select();

        // Lấy giỏ hàng của user
        $cartModel = new Cart();
        $cartItems = $cartModel->getCartItems($userId);

        // Nếu giỏ trống, redirect về view_cart
        if (empty($cartItems)) {
            header('Location: ' . BASE_URL . '?mod=client&action=view_cart');
            exit;
        }

        // Tính tổng tiền
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['subtotal'];
        }

        // Mảng errors (nếu trước đó submit gặp lỗi, sẽ lưu trong session)
        $errors = $_SESSION['checkout_errors'] ?? [];
        unset($_SESSION['checkout_errors']);

        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'checkout.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php';
    }

    /**
     * Xử lý dữ liệu POST khi user submit form checkout
     * Tạo order, order_items, xóa giỏ, redirect sang thank_you
     */
    public function submit()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Nếu chưa login, chuyển về login_form
        if (!isset($_SESSION['user']['id'])) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header('Location: ' . BASE_URL . '?action=login_form');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $cartModel = new Cart();
        $cartItems = $cartModel->getCartItems($userId);

        // Nếu giỏ rỗng, quay về view_cart
        if (empty($cartItems)) {
            header('Location: ' . BASE_URL . '?mod=client&action=view_cart');
            exit;
        }

        // Lấy dữ liệu từ form
        $name    = trim($_POST['name']    ?? '');
        $address = trim($_POST['address'] ?? '');
        $phone   = trim($_POST['phone']   ?? '');
        $note    = trim($_POST['note']    ?? '');

        // Validate đơn giản
        $errors = [];
        if ($name === '') {
            $errors[] = 'Vui lòng nhập tên người nhận.';
        }
        if ($address === '') {
            $errors[] = 'Vui lòng nhập địa chỉ giao hàng.';
        }
        if ($phone === '') {
            $errors[] = 'Vui lòng nhập số điện thoại.';
        }

        if (!empty($errors)) {
            $_SESSION['checkout_errors'] = $errors;
            header('Location: ' . BASE_URL . '?mod=client&action=checkout_form');
            exit;
        }

        // Tính tổng tiền
        $totalAmount = 0;
        foreach ($cartItems as $item) {
            $totalAmount += $item['subtotal'];
        }

        // Lưu đơn vào DB
        $orderModel     = new Order();
        $orderItemModel = new OrderItem();
        $pdo = $orderModel->getPdo();

        try {
            $pdo->beginTransaction();

            // Tạo bảng orders
            $orderId = $orderModel->createOrder(
                $userId,
                $name,
                $address,
                $phone,
                $note,
                $totalAmount
            );

            // Chuẩn bị mảng order_items
            $itemsToInsert = [];
            foreach ($cartItems as $item) {
                $itemsToInsert[] = [
                    'product_variant_id' => $item['product_variant_id'],
                    'quantity'           => $item['quantity'],
                    'price'              => $item['price']
                ];
            }

            // Chèn vào order_items
            $orderItemModel->createItems($orderId, $itemsToInsert);

            // Xóa giỏ hàng (DB)
            $cartModel->clearCart($userId);

            $pdo->commit();

            // Redirect sang thank_you
            header('Location: ' . BASE_URL . '?mod=client&action=thank_you&order_id=' . $orderId);
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $_SESSION['checkout_errors'] = ['Có lỗi khi tạo đơn hàng. Vui lòng thử lại.'];
            header('Location: ' . BASE_URL . '?mod=client&action=checkout_form');
            exit;
        }
    }

    /**
     * Trang cảm ơn sau khi tạo đơn thành công
     */
    public function thankYou()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Category (giống HomeController để menu)
        $categoryModel = new Category();
        $categories = $categoryModel->select();

        // Lấy order_id từ GET
        $orderId = isset($_GET['order_id']) ? (int) $_GET['order_id'] : null;
        if (!$orderId) {
            header('Location: ' . BASE_URL . '?mod=client&action=view_cart');
            exit;
        }

        $orderModel     = new Order();
        $orderItemModel = new OrderItem();

        $order = $orderModel->findOrderById($orderId);
        if (!$order) {
            header('Location: ' . BASE_URL . '?mod=client&action=view_cart');
            exit;
        }

        $orderItems = $orderItemModel->getItemsByOrder($orderId);

        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'thank_you.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php';
    }
}
