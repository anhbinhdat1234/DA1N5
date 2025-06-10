<!-- Hiển thị thông báo -->
<?php if (isset($_SESSION['success'])): ?>
  <div class="alert <?= $_SESSION['success'] ? 'alert-success' : 'alert-danger' ?>">
    <?= $_SESSION['msg'] ?>
  </div>
  <?php unset($_SESSION['success'], $_SESSION['msg']); ?>
<?php endif; ?>

<h4>Thông tin đơn hàng #<?= $order['id'] ?></h4>
<ul>
  <li>User ID: <?= $order['user_id'] ?></li>
  <li>Tổng tiền: <?= number_format($order['total']) ?></li>
  <li>Mã coupon: <?= htmlspecialchars($order['coupon_code']) ?></li>
  <li>Giảm giá: <?= $order['discount_amount'] ?></li>
  <li>Trạng thái:
    <form method="post" action="<?= BASE_URL_ADMIN . '&action=orders-update-status&id=' . $order['id'] ?>" style="display:inline">
      <select name="status" class="form-select d-inline w-auto">
        <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
        <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Đang xử lý</option>
        <option value="completed" <?= $order['status'] === 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
        <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Đã huỷ</option>
      </select>
      <button type="submit" class="btn btn-primary btn-sm ms-2">Cập nhật</button>
    </form>
  </li>
  <li>Ngày tạo: <?= $order['created_at'] ?></li>
</ul>

<h5>Sản phẩm trong đơn hàng</h5>
<table class="table table-bordered">
  <thead>
    <tr>
      <th>ID</th>
      <th>Sản phẩm</th>
      <th>Size</th>
      <th>Màu</th>
      <th>Số lượng</th>
      <th>Đơn giá</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($orderItems as $item): ?>
      <tr>
        <td><?= $item['id'] ?></td>
        <td><?= htmlspecialchars($item['product_name']) ?></td>
        <td><?= htmlspecialchars($item['size'] ?? '') ?></td>
        <td><?= htmlspecialchars($item['color'] ?? '') ?></td>
        <td><?= $item['quantity'] ?></td>
        <td><?= number_format($item['price']) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<a href="<?= BASE_URL_ADMIN . '&action=orders-index' ?>" class="btn btn-secondary">Quay lại</a>