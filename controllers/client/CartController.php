<?php
// controllers/client/CartController.php
require_once PATH_MODEL . 'Cart.php';

class CartController
{
    /**
     * Hiển thị giỏ hàng
     */
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId    = $_SESSION['user']['id'] ?? null;
        $cartModel = new Cart();
        $cartItems = $cartModel->getCartItems($userId);

        // Tính tổng tiền
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['subtotal'];
        }

        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'cart.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php';
    }

    /**
     * Thêm vào giỏ (có kiểm tra stock)
     */
    public function add()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $variantId = (int)($_POST['variant_id'] ?? 0);
        $qty       = max(1, (int)($_POST['quantity'] ?? 1));
        $userId    = $_SESSION['user']['id'] ?? null;
        $cartModel = new Cart();

        try {
            $cartModel->addToCart($userId, $variantId, $qty);
            $_SESSION['flash'] = 'Thêm vào giỏ thành công.';
        } catch (\Exception $e) {
            $_SESSION['flash'] = 'Lỗi: ' . $e->getMessage();
        }

        header('Location: ' . BASE_URL . '?action=view_cart');
        exit;
    }

    /**
     * Cập nhật số lượng hoặc xóa item
     */
    public function update()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId    = $_SESSION['user']['id'] ?? null;
        $cartModel = new Cart();

        if (isset($_POST['remove'])) {
            $toRemove = (int)$_POST['remove'];
            if ($userId !== null) {
                $cartModel->removeItem($toRemove);
            } else {
                unset($_SESSION['cart'][$toRemove]);
            }
        } elseif (isset($_POST['quantities'])) {
            foreach ($_POST['quantities'] as $cartId => $newQty) {
                $newQty = max(1, (int)$newQty);
                if ($userId !== null) {
                    $cartModel->updateQuantity((int)$cartId, $newQty);
                } else {
                    $_SESSION['cart'][$cartId] = $newQty;
                }
            }
        }

        header('Location: ' . BASE_URL . '?action=view_cart');
        exit;
    }

    /**
     * Xóa toàn bộ giỏ
     */
    public function clear()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId    = $_SESSION['user']['id'] ?? null;
        $cartModel = new Cart();
        $cartModel->clearCart($userId);

        header('Location: ' . BASE_URL . '?action=view_cart');
        exit;
    }
    public function applyCoupon()
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    $code = trim($_POST['coupon_code'] ?? '');
    $cartModel = new Cart();
    $total = array_sum(array_column($cartModel->getCartItems($_SESSION['user']['id'] ?? null), 'subtotal'));
    $couponModel = new Coupon();
    if ($c = $couponModel->validate($code, $total)) {
        $_SESSION['coupon']   = ['code' => $c['code'], 'id' => $c['id']];
        $_SESSION['discount'] = $c['type'] === 'percent'
            ? (int) floor($total * $c['value'] / 100)
            : (int) $c['value'];
        $_SESSION['msg_coupon'] = 'Áp dụng thành công!';
    } else {
        $_SESSION['msg_coupon'] = 'Mã không hợp lệ hoặc điều kiện không đủ.';
        unset($_SESSION['coupon'], $_SESSION['discount']);
    }
    header('Location: ' . BASE_URL . '?action=view_cart');
    exit;
}

public function removeCoupon()
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    unset($_SESSION['coupon'], $_SESSION['discount'], $_SESSION['msg_coupon']);
    header('Location: ' . BASE_URL . '?action=view_cart');
    exit;
}
}
