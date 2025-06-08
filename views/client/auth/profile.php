<?php
// views/client/auth/profile.php
?>
<div class="container py-5" style="max-width:800px;">
  <h2 class="mb-4">Thông tin tài khoản</h2>
  <table class="table table-borderless mb-5">
    <tr><th style="width:150px;">ID:</th><td><?= htmlspecialchars($userData['id']) ?></td></tr>
    <tr><th>Họ và tên:</th><td><?= htmlspecialchars($userData['name']) ?></td></tr>
    <tr><th>Email:</th><td><?= htmlspecialchars($userData['email']) ?></td></tr>
    <?php if (!empty($userData['created_at'])): ?>
    <tr><th>Ngày tạo:</th><td><?= date('d/m/Y H:i', strtotime($userData['created_at'])) ?></td></tr>
    <?php endif; ?>
  </table>

  <h3 class="mb-4">Lịch sử mua hàng</h3>
  <?php if (empty($ordersWithItems)): ?>
    <div class="alert alert-info">Bạn chưa có đơn hàng nào.</div>
  <?php else: ?>
    <?php foreach ($ordersWithItems as $entry): 
      $o = $entry['order']; ?>
      <div class="card mb-4">
        <div class="card-header">
          <strong>Đơn hàng #<?= htmlspecialchars($o['id']) ?></strong>
          <span class="text-muted float-end"><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></span>
        </div>
        <div class="card-body p-0">
          <table class="table mb-0">
            <thead>
              <tr>
                <th>Sản phẩm</th><th>Số lượng</th><th>Giá</th><th>Thành tiền</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($entry['items'] as $item): ?>
              <tr>
                <td><?= htmlspecialchars($item['product_name']) ?> <br>
                    <small>Size: <?= htmlspecialchars($item['size']) ?>, Màu: <?= htmlspecialchars($item['color']) ?></small>
                </td>
                <td><?= $item['quantity'] ?></td>
                <td><?= number_format($item['price'],0,',','.') ?>₫</td>
                <td><?= number_format($item['quantity'] * $item['price'],0,',','.') ?>₫</td>
              </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot>
              <tr>
                <th colspan="3" class="text-end">Tổng đơn:</th>
                <th><?= number_format($o['total'],0,',','.') ?>₫</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

  <a href="<?= BASE_URL ?>?action=logout" class="btn btn-danger">Đăng xuất</a>
</div>
