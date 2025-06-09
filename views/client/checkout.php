<?php
// views/client/checkout.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$errors = $errors ?? [];
$discount = $_SESSION['discount'] ?? 0;
$finalTotal = max(0, $total - $discount);
?>
<div class="container py-5">
  <h2 class="mb-4">Thanh toán</h2>

  <?php if ($errors): ?>
    <div class="alert alert-danger">
      <ul class="mb-0">
        <?php foreach ($errors as $err): ?>
          <li><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form action="<?= BASE_URL ?>?action=submit_checkout" method="post">
    <div class="row">
      <!-- Thông tin giao hàng -->
      <div class="col-lg-6 mb-4">
        <h5>Thông tin giao hàng</h5>
        <div class="mb-3">
          <label class="form-label">Địa chỉ</label>
          <textarea name="address" class="form-control" rows="3" required><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Số điện thoại</label>
          <input type="text" name="phone" class="form-control"
                 value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Ghi chú (tùy chọn)</label>
          <textarea name="note" class="form-control" rows="2"><?= htmlspecialchars($_POST['note'] ?? '') ?></textarea>
        </div>
      </div>

      <!-- Đơn hàng tóm tắt -->
      <div class="col-lg-6 mb-4">
        <h5>Đơn hàng của bạn</h5>
        <ul class="list-group mb-3">
          <?php foreach ($cartItems as $item): ?>
            <li class="list-group-item d-flex justify-content-between">
              <div>
                <?= htmlspecialchars($item['product_name']) ?><br>
                <small>Size: <?= htmlspecialchars($item['size']) ?>; Màu: <?= htmlspecialchars($item['color']) ?></small>
              </div>
              <span><?= $item['quantity'] ?> × <?= number_format($item['price'],0,',','.') ?>₫</span>
            </li>
          <?php endforeach; ?>
          <li class="list-group-item d-flex justify-content-between">
            <strong>Tổng</strong>
            <strong><?= number_format($total,0,',','.') ?>₫</strong>
          </li>
          <?php if ($discount>0): ?>
            <li class="list-group-item d-flex justify-content-between">
              <strong>Giảm giá</strong>
              <strong class="text-danger">-<?= number_format($discount,0,',','.') ?>₫</strong>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <strong>Thanh toán</strong>
              <strong class="text-success"><?= number_format($finalTotal,0,',','.') ?>₫</strong>
            </li>
          <?php endif; ?>
        </ul>
        <button type="submit" class="btn btn-success w-100">Xác nhận và đặt hàng</button>
      </div>
    </div>
  </form>
</div>
