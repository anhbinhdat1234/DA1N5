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

        // Nếu nhấn nút "Xóa" (remove = variantId hoặc cart_id)
        if (isset($_POST['remove'])) {
            $toRemove = (int) $_POST['remove'];

            if ($userId !== null) {
                // User đã login: xóa từ DB cart_items (dựa vào cart_id)
                $cartModel->removeItem($toRemove);
            } else {
                // Guest: xóa khỏi $_SESSION['cart'] (dựa vào variantId)
                if (isset($_SESSION['cart'][$toRemove])) {
                    unset($_SESSION['cart'][$toRemove]);
                }
            }
        }
        // Nếu không, xử lý cập nhật số lượng
        elseif (isset($_POST['quantities']) && is_array($_POST['quantities'])) {
            foreach ($_POST['quantities'] as $cartId => $newQty) {
                $newQty = (int) $newQty;
                if ($newQty < 1) {
                    $newQty = 1;
                }

                if ($userId !== null) {
                    // User đã login: cập nhật trong DB
                    $cartModel->updateQuantity((int)$cartId, $newQty);
                } else {
                    // Guest: cập nhật trong $_SESSION['cart'] (cartId thực ra là variantId)
                    if (isset($_SESSION['cart'][$cartId])) {
                        $_SESSION['cart'][$cartId] = $newQty;
                    }
                }
            }
        }

        header('Location: ' . BASE_URL . '?mod=client&action=view_cart');
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
