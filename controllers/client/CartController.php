<?php
class CartController
{
    /**
     * Hiển thị trang giỏ hàng
     */
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

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
     * Thêm vào giỏ hàng (POST)
     */
    public function add()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $variantId = isset($_POST['variant_id']) ? (int) $_POST['variant_id'] : 0;
        $qty       = isset($_POST['quantity'])   ? max(1, (int) $_POST['quantity']) : 1;
        $userId    = $_SESSION['user']['id'] ?? null;

        $cartModel = new Cart();
        $cartModel->addToCart($userId, $variantId, $qty);

        header('Location: ' . BASE_URL . '?mod=client&action=view_cart');
        exit;
    }

    /**
     * Cập nhật số lượng hoặc xóa item trong giỏ
     */
public function update()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $userId    = $_SESSION['user']['id'] ?? null;
    $cartModel = new Cart();
    $couponModel = new Coupon();  // nếu chưa autoload, require PATH_MODEL . 'Coupon.php'

    // 1) Lấy tổng tiền hiện tại để validate coupon
    $cartItems = $cartModel->getCartItems($userId);
    $total = 0;
    foreach ($cartItems as $it) {
        $total += $it['subtotal'];
    }

    // 2) Áp dụng coupon
    if (isset($_POST['apply_coupon'])) {
        $code   = trim($_POST['coupon_code'] ?? '');
        $c      = $couponModel->validate($code, $total);

        if (!$c) {
            $_SESSION['msg_coupon'] = 'Mã không hợp lệ, đã hết hạn hoặc chưa đủ điều kiện.';
        } else {
            // Tính discount
            if ($c['type'] === 'percent') {
                $discount = (int) floor($total * $c['value'] / 100);
            } else {
                $discount = (int) $c['value'];
            }
            // Lưu vào session
            $_SESSION['coupon']   = ['id' => $c['id'], 'code' => $c['code']];
            $_SESSION['discount'] = $discount;
            $_SESSION['msg_coupon'] = "Áp dụng thành công: -". number_format($discount,0,',','.') ."₫";
            // Tăng lượt dùng (nếu bạn muốn track ngay khi apply)
            $couponModel->incrementUsage($c['id']);
        }
        header('Location: ' . BASE_URL . '?action=view_cart');
        exit;
    }

    // 3) Xóa coupon
    if (isset($_POST['remove_coupon'])) {
        unset($_SESSION['coupon'], $_SESSION['discount'], $_SESSION['msg_coupon']);
        header('Location: ' . BASE_URL . '?action=view_cart');
        exit;
    }

    // 4) Xử lý xóa item hoặc cập nhật số lượng như trước
    if (isset($_POST['remove'])) {
        $toRemove = (int) $_POST['remove'];
        if ($userId !== null) {
            $cartModel->removeItem($toRemove);
        } else {
            unset($_SESSION['cart'][$toRemove]);
        }
    } elseif (isset($_POST['quantities']) && is_array($_POST['quantities'])) {
        foreach ($_POST['quantities'] as $cartId => $newQty) {
            $newQty = max(1, (int) $newQty);
            if ($userId !== null) {
                $cartModel->updateQuantity($cartId, $newQty);
            } else {
                $_SESSION['cart'][$cartId] = $newQty;
            }
        }
    }

    header('Location: ' . BASE_URL . '?action=view_cart');
    exit;
}
    /**
     * Xóa toàn bộ giỏ hàng
     */
    public function clear()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userId = $_SESSION['user']['id'] ?? null;
        $cartModel = new Cart();

        if ($userId !== null) {
            $cartModel->clearCart($userId);
        } else {
            unset($_SESSION['cart']);
        }

        header('Location: ' . BASE_URL . '?mod=client&action=view_cart');
        exit;
    }
}
