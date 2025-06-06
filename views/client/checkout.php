<?php
?>
<div class="container py-5">
    <h2 class="mb-4">Thanh toán</h2>

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
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Địa chỉ</label>
                    <textarea name="address" class="form-control" rows="3" required><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ghi chú (tuỳ chọn)</label>
                    <textarea name="note" class="form-control" rows="2"><?= htmlspecialchars($_POST['note'] ?? '') ?></textarea>
                </div>
            </div>

            <!-- Giỏ hàng tóm tắt -->
            <div class="col-lg-6 mb-4">
                <h5>Đơn hàng của bạn</h5>
                <ul class="list-group mb-3">
                    <?php foreach ($cartItems as $item): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <?= htmlspecialchars($item['product_name']) ?>
                                <br><small>Màu: <?= htmlspecialchars($item['color']) ?>, <?= htmlspecialchars($item['size']) ?></small>
                            </div>
                            <span class="badge bg-primary rounded-pill">
                                <?= htmlspecialchars($item['quantity']) ?> x <?= number_format($item['price'], 0, ',', '.') ?>₫
                            </span>
                        </li>
                    <?php endforeach; ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <strong>Tổng</strong>
                        <strong><?= number_format($total, 0, ',', '.') ?>₫</strong>
                    </li>
                </ul>
                <button type="submit" class="btn btn-success w-100">Xác nhận và đặt hàng</button>
            </div>
        </div>
    </form>
</div>
