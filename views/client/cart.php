<?php
// views/client/cart.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="container py-5">
  <!-- Flash messages -->
  <?php if (!empty($_SESSION['flash'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash']) ?></div>
    <?php unset($_SESSION['flash']); ?>
  <?php endif; ?>
  <?php if (!empty($_SESSION['msg_coupon'])): ?>
    <div class="alert alert-info"><?= htmlspecialchars($_SESSION['msg_coupon']) ?></div>
    <?php unset($_SESSION['msg_coupon']); ?>
  <?php endif; ?>

  <h2 class="mb-4">Giỏ hàng của bạn</h2>

  <?php if (empty($cartItems)): ?>
    <div class="alert alert-info">Giỏ hàng đang trống.</div>
    <a href="<?= BASE_URL ?>" class="btn btn-primary">Quay về trang chủ</a>
  <?php else: ?>

    <form action="<?= BASE_URL ?>?action=apply_coupon" method="post" class="mb-4" style="max-width: 400px;">
      <div class="input-group">
        <input type="text"
               name="coupon_code"
               class="form-control"
               placeholder="Nhập mã khuyến mãi"
               value="<?= htmlspecialchars($_SESSION['coupon']['code'] ?? '') ?>">
        <button class="btn btn-outline-secondary" type="submit" name="apply_coupon">Áp dụng</button>
        <?php if (!empty($_SESSION['coupon'])): ?>
          <a href="<?= BASE_URL ?>?action=remove_coupon" class="btn btn-outline-danger">Xóa mã</a>
        <?php endif; ?>
      </div>
    </form>

    <form action="<?= BASE_URL ?>?action=update_cart" method="post">
      <table class="table align-middle">
        <thead>
          <tr>
            <th style="width:100px;">Ảnh</th>
            <th>Sản phẩm</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Thành tiền</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cartItems as $item): ?>
            <tr>
              <td>
                <img src="<?= BASE_URL . $item['thumbnail'] ?>"
                     alt="<?= htmlspecialchars($item['product_name']) ?>"
                     class="img-fluid" style="object-fit:cover;width:100px;height:100px">
              </td>
              <td>
                <a href="<?= BASE_URL ?>?action=product_ct&id=<?= $item['product_id'] ?>">
                  <?= htmlspecialchars($item['product_name']) ?>
                </a><br>
                <small>Color: <?= htmlspecialchars($item['color']) ?>, Size: <?= htmlspecialchars($item['size']) ?></small>
              </td>
              <td><?= number_format($item['price'],0,',','.') ?>₫</td>
              <td>
                <input type="number"
                       name="quantities[<?= $item['cart_id'] ?>]"
                       value="<?= $item['quantity'] ?>"
                       min="1"
                       class="form-control"
                       style="width:80px">
              </td>
              <td><?= number_format($item['subtotal'],0,',','.') ?>₫</td>
              <td>
                <button type="submit"
                        name="remove"
                        value="<?= $item['cart_id'] ?>"
                        class="btn btn-sm btn-outline-danger">Xóa</button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div class="d-flex justify-content-between align-items-center mt-4">
        <div>
          <button type="submit" class="btn btn-primary me-2">Cập nhật giỏ hàng</button>
          <a href="<?= BASE_URL ?>?action=clear_cart" class="btn btn-outline-danger">Xóa toàn bộ</a>
        </div>
        <div class="text-end">
          <?php
            $total     = array_sum(array_column($cartItems,'subtotal'));
            $discount  = $_SESSION['discount'] ?? 0;
            $finalTotal= max(0, $total - $discount);
          ?>
          <h5>Tổng cộng: <span class="text-danger"><?= number_format($total,0,',','.') ?>₫</span></h5>
          <?php if ($discount>0): ?>
            <h6>Giảm giá: <span class="text-danger">-<?= number_format($discount,0,',','.') ?>₫</span></h6>
            <h5>Thanh toán: <span class="text-success"><?= number_format($finalTotal,0,',','.') ?>₫</span></h5>
          <?php endif; ?>
          <a href="<?= BASE_URL ?>?action=checkout_form" class="btn btn-success mt-2">Tiến hành thanh toán</a>
        </div>
      </div>
    </form>
  <?php endif; ?>
</div>
