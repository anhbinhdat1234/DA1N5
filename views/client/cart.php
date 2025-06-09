<?php
// views/client/cart.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$flash      = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);
$msg_coupon = $_SESSION['msg_coupon'] ?? '';
unset($_SESSION['msg_coupon']);

// $cartItems phải được controller truyền vào
$total      = array_sum(array_column($cartItems, 'subtotal'));
$discount   = $_SESSION['discount'] ?? 0;
$finalTotal = max(0, $total - $discount);
?>
<div class="container py-5">
  <?php if ($flash): ?>
    <div class="alert alert-success"><?= htmlspecialchars($flash) ?></div>
  <?php endif; ?>
  <?php if ($msg_coupon): ?>
    <div class="alert alert-info"><?= htmlspecialchars($msg_coupon) ?></div>
  <?php endif; ?>

  <h2 class="mb-4">Giỏ hàng của bạn</h2>

  <?php if (empty($cartItems)): ?>
    <div class="alert alert-info">Giỏ hàng đang trống.</div>
    <a href="<?= BASE_URL ?>" class="btn btn-primary">Quay về trang chủ</a>
  <?php else: ?>

    <!-- Mã giảm giá -->
    <form action="<?= BASE_URL ?>?action=apply_coupon" method="post" class="mb-4" style="max-width:400px;">
      <div class="input-group">
        <input type="text" name="coupon_code" class="form-control"
               placeholder="Nhập mã khuyến mãi"
               value="<?= htmlspecialchars($_SESSION['coupon']['code'] ?? '') ?>">
        <button class="btn btn-outline-secondary" type="submit" name="apply_coupon">Áp dụng</button>
        <?php if (!empty($_SESSION['coupon'])): ?>
          <a href="<?= BASE_URL ?>?action=remove_coupon" class="btn btn-outline-danger">Xóa mã</a>
        <?php endif; ?>
      </div>
    </form>

    <!-- Danh sách giỏ hàng -->
    <form action="<?= BASE_URL ?>?action=update_cart" method="post">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>Ảnh</th>
            <th>Sản phẩm</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Tồn kho</th>
            <th>Thành tiền</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cartItems as $item): 
            $stock = (int)($item['stock'] ?? 0);
          ?>
          <tr data-stock="<?= $stock ?>">
            <td>
              <img src="<?= BASE_URL . $item['thumbnail'] ?>"
                   alt="" style="width:100px;height:100px;object-fit:cover;">
            </td>
            <td>
              <a href="<?= BASE_URL ?>?action=product_ct&id=<?= $item['product_id'] ?>">
                <?= htmlspecialchars($item['product_name']) ?>
              </a><br>
              <small>Color: <?= htmlspecialchars($item['color']) ?>; Size: <?= htmlspecialchars($item['size']) ?></small>
            </td>
            <td><?= number_format($item['price'],0,',','.') ?>₫</td>
            <td>
              <input type="number"
                     name="quantities[<?= $item['cart_id'] ?>]"
                     value="<?= $item['quantity'] ?>"
                     min="1"
                     max="<?= $stock ?>"
                     class="form-control qty-input"
                     style="width:80px;"
                     required>
              <div class="form-text text-danger small d-none qty-warning">
                Tối đa <?= $stock ?> sản phẩm
              </div>
            </td>
            <td><?= $stock ?></td>
            <td><?= number_format($item['subtotal'],0,',','.') ?>₫</td>
            <td>
              <button type="submit" name="remove" value="<?= $item['cart_id'] ?>"
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

<script>
// Kiểm tra và giới hạn số lượng theo tồn kho
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('tr[data-stock]').forEach(row => {
    const stock = +row.dataset.stock;
    const input = row.querySelector('.qty-input');
    const warn  = row.querySelector('.qty-warning');

    input.addEventListener('input', () => {
      const v = +input.value || 0;
      if (v > stock) {
        warn.classList.remove('d-none');
      } else {
        warn.classList.add('d-none');
      }
    });

    input.addEventListener('blur', () => {
      let v = +input.value || 0;
      if (v > stock) input.value = stock;
      if (v < 1) input.value = 1;
      warn.classList.add('d-none');
    });
  });
});
</script>
