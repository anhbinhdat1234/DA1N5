<?php
require_once PATH_MODEL . 'Order.php';
require_once PATH_MODEL . 'OrderItem.php';

class OrderController
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user']['id'])) {
            header('Location: ' . BASE_URL . '?action=login_form');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $orders = (new Order())->getOrdersByUser($userId);

        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'orders/list.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php';
    }

    public function detail()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user']['id'])) {
            header('Location: ' . BASE_URL . '?action=login_form');
            exit;
        }

        $orderId = (int)($_GET['id'] ?? 0);
        $userId  = $_SESSION['user']['id'];

        $orderModel = new Order();
        $order      = $orderModel->getOrderDetails($orderId, $userId);

        if (!$order) {
            $_SESSION['error'] = 'Đơn hàng không tồn tại';
            header('Location: ' . BASE_URL . '?action=orders');
            exit;
        }

        $orderItems = (new OrderItem())->getItemsByOrder($orderId);

        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'orders/detail.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php';
    }

    public static function getStatusText(int $status): string
    {
        return match ($status) {
            0 => 'Chờ xác nhận',
            1 => 'Đang xử lý',
            2 => 'Đang giao hàng',
            3 => 'Đã giao hàng',
            4 => 'Đã hủy',
            default => 'Không xác định',
        };
    }

    public static function getShippingStatusText(int $shippingStatus): string
    {
        return match ($shippingStatus) {
            0 => 'Chờ giao hàng',
            1 => 'Đang vận chuyển',
            2 => 'Đã giao hàng',
            3 => 'Giao hàng thất bại',
            default => 'Không xác định',
        };
    }

    public function updateItem()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user']['id'])) {
            header('Location: ' . BASE_URL . '?action=login_form');
            exit;
        }

        $orderId  = (int)($_POST['order_id'] ?? 0);
        $itemId   = (int)($_POST['item_id'] ?? 0);
        $quantity = max(1, (int)($_POST['quantity'] ?? 1));

        $orderModel = new Order();
        $order      = $orderModel->getOrderDetails($orderId, $_SESSION['user']['id']);

        if ($order && $order['status'] === 0) {
            (new OrderItem())->updateQuantity($itemId, $quantity);
        }

        header('Location: ' . BASE_URL . '?action=order_detail&id=' . $orderId);
        exit;
    }

    public function deleteItem()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['user']['id'])) {
            header('Location: ' . BASE_URL . '?action=login_form');
            exit;
        }

        $orderId = (int)($_POST['order_id'] ?? 0);
        $itemId  = (int)($_POST['item_id'] ?? 0);

        $orderModel = new Order();
        $order      = $orderModel->getOrderDetails($orderId, $_SESSION['user']['id']);

        if ($order && $order['status'] === 0) {
            (new OrderItem())->deleteItem($itemId);
        }

        header('Location: ' . BASE_URL . '?action=order_detail&id=' . $orderId);
        exit;
    }
}
