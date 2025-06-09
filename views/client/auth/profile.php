<?php
// views/client/auth/profile.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$updated = isset($_GET['updated']);
$errors  = $_SESSION['profile_errors'] ?? [];
unset($_SESSION['profile_errors']);
?>
<div class="container py-5" style="max-width:900px;">
  <div class="card mb-5 shadow-sm">
    <div class="card-body">
      <h2 class="card-title mb-4">Thông tin tài khoản</h2>
      <?php if ($errors): ?>
        <div class="alert alert-danger">
          <ul class="mb-0">
            <?php foreach ($errors as $err): ?>
              <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>
      <form id="profile-form" method="post" action="<?= BASE_URL ?>?action=profile">
        <div class="row g-3 mb-4">
          <div class="col-md-2 fw-bold">ID:</div>
          <div class="col-md-10"><?= htmlspecialchars($userData['id']) ?></div>

          <div class="col-md-2 fw-bold">Họ và tên:</div>
          <div class="col-md-10">
            <input type="text" name="name" value="<?= htmlspecialchars($userData['name']) ?>"
                   class="form-control form-control-sm" disabled required>
          </div>

          <div class="col-md-2 fw-bold">Email:</div>
          <div class="col-md-10">
            <input type="email" value="<?= htmlspecialchars($userData['email']) ?>"
                   class="form-control form-control-sm" disabled>
          </div>

          <div class="col-md-2 fw-bold">SĐT:</div>
          <div class="col-md-10">
            <input type="text" name="phone" value="<?= htmlspecialchars($userData['phone'] ?? '') ?>"
                   class="form-control form-control-sm" disabled>
          </div>

          <div class="col-md-2 fw-bold align-self-start">Địa chỉ:</div>
          <div class="col-md-10">
            <textarea name="address" class="form-control form-control-sm" rows="2" disabled><?= htmlspecialchars($userData['address'] ?? '') ?></textarea>
          </div>
        </div>

        <div class="text-end">
          <button type="button" id="btn-edit" class="btn btn-outline-primary btn-sm">Chỉnh sửa hồ sơ</button>
          <button type="submit" id="btn-save" class="btn btn-primary btn-sm d-none">Lưu hồ sơ</button>
          <button type="button" id="btn-cancel" class="btn btn-secondary btn-sm d-none">Hủy</button>
        </div>
      </form>
      <?php if ($updated): ?>
        <div class="mt-3 alert alert-success py-2">Cập nhật thành công!</div>
      <?php endif; ?>
    </div>
  </div>

  <h3 class="mb-4">Lịch sử mua hàng</h3>
  <?php if (empty($ordersWithItems)): ?>
    <div class="alert alert-info">Bạn chưa có đơn hàng nào.</div>
  <?php else: ?>
    <?php foreach ($ordersWithItems as $entry):
      $o = $entry['order'];
      $status = $o['status'];
      $badgeClass = match($status) {
        'pending' => 'warning',
        'processing' => 'info',
        'shipped' => 'primary',
        'delivered' => 'success',
        'cancelled' => 'danger',
        default => 'secondary',
      };
      $shipAddr = htmlspecialchars($o['shipping_address'] ?? '');
      $shipPhone = htmlspecialchars($o['shipping_phone'] ?? '');
    ?>
      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-1">Đơn #<?= $o['id'] ?></h5>
              <small class="text-muted"><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></small>
            </div>
            <span class="badge bg-<?= $badgeClass ?>"><?= ucfirst($status) ?></span>
          </div>
          <?php if ($o['coupon_code']): ?>
            <div class="mt-2">
              <span class="text-muted">Mã giảm:</span>
              <span class="text-danger fw-semibold"><?= htmlspecialchars($o['coupon_code']) ?></span>
              <span class="fw-semibold">-<?= number_format($o['discount_amount'],0,',','.') ?>₫</span>
            </div>
          <?php endif; ?>

          <div class="row mt-3">
            <div class="col-md-8">
              <p class="mb-1"><strong>Địa chỉ:</strong> <span id="addr-display-<?= $o['id'] ?>"><?= $shipAddr ?></span></p>
              <p><strong>SĐT:</strong> <span id="phone-display-<?= $o['id'] ?>"><?= $shipPhone ?></span></p>
            </div>
            <?php if ($status === 'pending'): ?>
            <div class="col-md-4 text-end">
              <button class="btn btn-link btn-sm" onclick="toggleEdit(<?= $o['id'] ?>)">Chỉnh sửa</button>
              <form action="<?= BASE_URL ?>?action=cancel_order" method="post" class="d-inline">
                <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                <button class="btn btn-danger btn-sm">Hủy đơn</button>
              </form>
            </div>
            <?php endif; ?>
          </div>

          <?php if ($status === 'pending'): ?>
          <form action="<?= BASE_URL ?>?action=update_order_address" method="post" id="addr-form-<?= $o['id'] ?>" class="mt-3 d-none border p-3 rounded">
            <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
            <div class="mb-2">
              <label class="form-label">Địa chỉ mới</label>
              <textarea name="new_address" class="form-control form-control-sm" rows="2" required><?= $shipAddr ?></textarea>
            </div>
            <div class="mb-2">
              <label class="form-label">SĐT mới</label>
              <input type="text" name="new_phone" class="form-control form-control-sm" value="<?= $shipPhone ?>" required>
            </div>
            <div class="text-end">
              <button type="submit" class="btn btn-success btn-sm">Lưu thay đổi</button>
              <button type="button" class="btn btn-secondary btn-sm" onclick="toggleEdit(<?= $o['id'] ?>)">Hủy</button>
            </div>
          </form>
          <?php endif; ?>
        </div>

        <div class="card-body p-0">
          <table class="table mb-0">
            <thead class="table-light">
              <tr>
                <th>SP</th>
                <th class="text-center">SL</th>
                <th class="text-end">Giá</th>
                <th class="text-end">Tổng</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($entry['items'] as $it):
                $size = htmlspecialchars($it['variant_size'] ?? $it['size'] ?? '');
                $color = htmlspecialchars($it['variant_color'] ?? $it['color'] ?? '');
              ?>
              <tr>
                <td><strong><?= htmlspecialchars($it['product_name']) ?></strong><br><small class="text-muted">Size: <?= $size ?>; Màu: <?= $color ?></small></td>
                <td class="text-center"><?= $it['quantity'] ?></td>
                <td class="text-end"><?= number_format($it['price'],0,',','.') ?>₫</td>
                <td class="text-end"><?= number_format($it['quantity']*$it['price'],0,',','.') ?>₫</td>
              </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot class="table-light">
              <tr>
                <td colspan="2"></td>
                <th colspan="2" class="text-end">Tổng: <?= number_format($o['total'],0,',','.') ?>₫</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

  <div class="text-end">
    <a href="<?= BASE_URL ?>?action=logout" class="btn btn-outline-danger">Đăng xuất</a>
  </div>
</div>

<script>
function toggleEdit(id) {
  document.getElementById(`addr-form-${id}`).classList.toggle('d-none');
}
</script>
