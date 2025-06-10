<!-- Hiển thị thông báo -->
<?php if (isset($_SESSION['success'])): ?>
  <?php require_once __DIR__ . '../../../../configs/helper.php'; ?>


  <div class="alert <?= $_SESSION['success'] ? 'alert-success' : 'alert-danger' ?>">
    <?= $_SESSION['msg'] ?>
  </div>
  <?php unset($_SESSION['success'], $_SESSION['msg']); ?>
<?php endif; ?>

<h2>Danh sách đơn hàng</h2>
<table class="table table-bordered">
  <thead>
    <tr>
      <th>#</th>
      <th>Họ tên</th>
      <th>Mã coupon</th>
      <th>Giảm giá</th>
      <th>Tổng tiền</th>
      <th>Trạng thái</th>
      <th>Ngày tạo</th>
      <th>Thao tác</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($orders as $o): ?>
      <tr>
        <td><?= $o['id'] ?></td>
        <td><?= htmlspecialchars($o['user_name'] ?? '') ?></td>
        <td><?= htmlspecialchars($o['coupon_code'] ?? '') ?></td>
        <td><?= $o['discount_amount'] ?></td>
        <td><?= number_format($o['total']) ?></td>
        <td><?= orderStatusBadge($o['status']) ?></td>
        <td><?= $o['created_at'] ?></td>
        <td>
          <a href="<?= BASE_URL_ADMIN . '&action=orders-show&id=' . $o['id'] ?>" class="btn btn-info btn-sm">Xem</a>
          <a href="<?= BASE_URL_ADMIN . '&action=orders-delete&id=' . $o['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Xóa đơn này?')">Xóa</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>