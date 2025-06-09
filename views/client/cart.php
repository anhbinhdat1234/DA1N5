<?php
// views/client/cart.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="container py-5">
    <h2 class="mb-4">Giỏ hàng của bạn</h2>

    <?php if (empty($cartItems)): ?>
        <div class="alert alert-info">Giỏ hàng đang trống.</div>
        <a href="<?= BASE_URL ?>" class="btn btn-primary">Quay về trang chủ</a>
    <?php else: ?>
        <form action="?action=update_cart" method="post">
            <!-- Coupon Code -->
            <?php if (isset($_SESSION['msg_coupon'])): ?>
                <div class="alert alert-info"><?= htmlspecialchars($_SESSION['msg_coupon']) ?></div>
                <?php unset($_SESSION['msg_coupon']); ?>
            <?php endif; ?>
            <div class="mb-4">
<div class="input-group" style="max-width:400px;">
    <input 
        type="text" 
        name="coupon_code" 
        class="form-control" 
        placeholder="Nhập mã khuyến mãi"
        value="<?= htmlspecialchars($_SESSION['coupon']['code'] ?? '') ?>"
    >
    <button 
        class="btn btn-outline-secondary" 
        type="submit" 
        name="apply_coupon"
    >Áp dụng</button>
    <?php if (!empty($_SESSION['coupon'])): ?>
        <button 
            class="btn btn-outline-danger" 
            type="submit" 
            name="remove_coupon"
        >Xóa mã</button>
    <?php endif; ?>
</div>
<?php if (isset($_SESSION['msg_coupon'])): ?>
    <div class="alert alert-info mt-2">
      <?= htmlspecialchars($_SESSION['msg_coupon']) ?>
    </div>
    <?php unset($_SESSION['msg_coupon']); ?>
<?php endif; ?>

            </div>

            <table class="table align-middle">
                <thead>
                    <tr>
                        <th scope="col" style="width:100px;">Ảnh</th>
                        <th scope="col">Sản phẩm</th>
                        <th scope="col">Giá</th>
                        <th scope="col">Số lượng</th>
                        <th scope="col">Thành tiền</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr>
                            <td>
                                <img 
                                    src="<?= BASE_URL . '/' . htmlspecialchars($item['thumbnail']) ?>" 
                                    class="img-fluid" 
                                    alt="<?= htmlspecialchars($item['product_name']) ?>" 
                                    style="object-fit:cover; width:100px; height:100px;"
                                >
                            </td>
                            <td>
                                <a href="<?= BASE_URL ?>?action=product_ct&id=<?= $item['product_id'] ?>">
                                    <?= htmlspecialchars($item['product_name']) ?>
                                </a><br>
                                <small>Màu: <?= htmlspecialchars($item['color']) ?>, Size: <?= htmlspecialchars($item['size']) ?></small>
                            </td>
                            <td>
                                <?= number_format($item['price'], 0, ',', '.') ?>₫
                            </td>
                            <td>
                                <input 
                                    type="number" 
                                    name="quantities[<?= $item['cart_id'] ?>]" 
                                    value="<?= (int)$item['quantity'] ?>" 
                                    min="1" 
                                    class="form-control" 
                                    style="width:80px;"
                                >
                            </td>
                            <td>
                                <?= number_format($item['subtotal'], 0, ',', '.') ?>₫
                            </td>
                            <td>
                                <button 
                                    type="submit" 
                                    name="remove" 
                                    value="<?= $item['cart_id'] ?>" 
                                    class="btn btn-sm btn-outline-danger"
                                    title="Xóa sản phẩm"
                                >
                                    Xóa
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <button type="submit" class="btn btn-primary me-2">Cập nhật giỏ hàng</button>
                    <a href="?action=clear_cart" class="btn btn-outline-danger">Xóa toàn bộ</a>
                </div>
                <div class="text-end">
                    <?php if (!empty($_SESSION['discount'])): ?>
                        <h6>Giảm giá: <span class="text-danger">-<?= number_format($_SESSION['discount'],0,',','.') ?>₫</span></h6>
                        <?php $finalTotal = $total - $_SESSION['discount']; ?>
                        <h5>Tổng còn: <span class="text-danger"><?= number_format($finalTotal,0,',','.') ?>₫</span></h5>
                    <?php else: ?>
                        <h5>Tổng cộng: <span class="text-danger"><?= number_format($total, 0, ',', '.') ?>₫</span></h5>
                    <?php endif; ?>
                    <a href="?action=checkout_form" class="btn btn-success mt-2">Tiến hành thanh toán</a>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>
