<?php
// views/client/checkout.php

// 1) Khởi session và lấy errors từ session nếu có
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$errors = $_SESSION['checkout_errors'] ?? [];
unset($_SESSION['checkout_errors']);

// 2) Nếu controller chưa truyền sẵn, bạn có thể lấy cartItems & totals từ session/model ở đây
//    Tuy nhiên thông thường controller sẽ truyền $cartItems và $total.
//    Nếu cần, bạn có thể uncomment đoạn sau để tự tính:
// $userId    = $_SESSION['user']['id'] ?? null;
// $cartModel = new Cart();
// $cartItems = $cartModel->getCartItems($userId);
// $total     = array_sum(array_column($cartItems, 'subtotal'));

// 3) Lấy discount và finalTotal từ session (controller form đã set) hoặc fallback
$discount   = $discount   ?? $_SESSION['discount'] ?? 0;
$finalTotal = $finalTotal ?? max(0, $total - $discount);
?>
<div class="container py-5">
    <h2 class="mb-4">Thanh toán</h2>

    <!-- Hiển thị lỗi nếu có -->
    <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $err): ?>
            <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>?mod=client&action=submit_checkout" method="post">
        <div class="row">
            <!-- Thông tin giao hàng -->
            <div class="col-lg-6 mb-4">
                <h5>Thông tin giao hàng</h5>

                <div class="mb-3">
                    <label class="form-label">Tên người nhận</label>
                    <input type="text"
                           name="name"
                           class="form-control"
                           value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Địa chỉ</label>
                    <textarea name="address"
                              class="form-control"
                              rows="3"
                              required><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text"
                           name="phone"
                           class="form-control"
                           value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ghi chú (tuỳ chọn)</label>
                    <textarea name="note"
                              class="form-control"
                              rows="2"><?= htmlspecialchars($_POST['note'] ?? '') ?></textarea>
                </div>
            </div>

            <!-- Giỏ hàng tóm tắt -->
            <div class="col-lg-6 mb-4">
                <h5>Đơn hàng của bạn</h5>
                <ul class="list-group mb-3">
                    <?php foreach ($cartItems as $item): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <?= htmlspecialchars($item['product_name']) ?><br>
                            <small>
                                Màu: <?= htmlspecialchars($item['color']) ?>,
                                Size: <?= htmlspecialchars($item['size']) ?>
                            </small>
                        </div>
                        <span class="badge bg-primary rounded-pill">
                            <?= $item['quantity'] ?> × <?= number_format($item['price'], 0, ',', '.') ?>₫
                        </span>
                    </li>
                    <?php endforeach; ?>

                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Tổng</strong>
                        <strong><?= number_format($total, 0, ',', '.') ?>₫</strong>
                    </li>

                    <?php if ($discount > 0): ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Giảm giá</strong>
                        <strong class="text-danger">-<?= number_format($discount, 0, ',', '.') ?>₫</strong>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Thanh toán</strong>
                        <strong class="text-success"><?= number_format($finalTotal, 0, ',', '.') ?>₫</strong>
                    </li>
                    <?php endif; ?>
                </ul>

                <!-- Gửi thông tin discount và final_total cho controller nếu cần -->
                <input type="hidden" name="discount"    value="<?= $discount ?>">
                <input type="hidden" name="final_total" value="<?= $finalTotal ?>">

                <button type="submit" class="btn btn-success w-100">
                    Xác nhận và đặt hàng
                </button>
            </div>
        </div>
    </form>
</div>
