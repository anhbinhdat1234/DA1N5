<?php
// views/client/auth/profile.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if profile vừa cập nhật
$updated = isset($_GET['updated']);
?>
<div class="container py-5" style="max-width:900px;">

  <!-- Thông tin tài khoản -->
  <div class="card mb-5 shadow-sm">
    <div class="card-body">
      <h2 class="card-title mb-4">Thông tin tài khoản</h2>
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

        <div class="d-flex gap-2">
          <button type="button" id="btn-edit" class="btn btn-outline-primary btn-sm">Chỉnh sửa</button>
          <button type="submit" id="btn-save" class="btn btn-primary btn-sm d-none">Lưu</button>
          <button type="button" id="btn-cancel" class="btn btn-secondary btn-sm d-none">Hủy</button>
        </div>
      </form>

      <?php if ($updated): ?>
        <div class="mt-3 alert alert-success py-2">Cập nhật thông tin thành công!</div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Lịch sử đơn hàng -->
  <h3 class="mb-4">Lịch sử mua hàng</h3>
  <?php if (empty($ordersWithItems)): ?>
    <div class="alert alert-info">Bạn chưa có đơn hàng nào.</div>
  <?php else: ?>
    <?php foreach ($ordersWithItems as $entry):
      $o = $entry['order'];
      $status = $o['status'] ?? '';
      $badgeClass = match($status) {
        'pending'    => 'warning',
        'processing' => 'info',
        'shipped'    => 'primary',
        'delivered'  => 'success',
        'cancelled'  => 'danger',
        default      => 'secondary',
      };
      $badgeLabel = ucfirst($status);
      $shipAddr   = $o['shipping_address'] ?? $o['address'] ?? ($_SESSION['user']['address'] ?? '');
    ?>
      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-1">Đơn hàng #<?= htmlspecialchars($o['id']) ?></h5>
              <small class="text-muted"><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></small>
            </div>
            <span class="badge bg-<?= $badgeClass ?>"><?= $badgeLabel ?></span>
          </div>

          <?php if (!empty($o['coupon_code'])): ?>
            <div class="mt-2">
              <span class="text-muted">Mã giảm giá:</span>
              <span class="fw-semibold text-danger"><?= htmlspecialchars($o['coupon_code']) ?></span>
              &nbsp;&minus;
              <span class="fw-semibold text-danger"><?= number_format($o['discount_amount'],0,',','.') ?>₫</span>
            </div>
          <?php endif; ?>

          <div class="mt-3 d-flex flex-wrap align-items-center">
            <div class="me-3">
              <span class="text-muted">Địa chỉ:</span>
              <span id="addr-display-<?= $o['id'] ?>"><?= htmlspecialchars($shipAddr) ?></span>
            </div>
          </div>

          <?php if ($status === 'pending'): ?>
            <form action="<?= BASE_URL ?>?action=update_order_address" method="post"
                  id="addr-form-<?= $o['id'] ?>" class="mt-2 d-none">
              <input type="hidden" name="action_type" value="update_address">
              <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
              <div class="input-group input-group-sm">
                <textarea name="new_address" class="form-control" rows="2" required><?= htmlspecialchars($shipAddr) ?></textarea>
                <button class="btn btn-primary" type="submit">Lưu</button>
                <button class="btn btn-secondary" type="button" onclick="toggleEdit(<?= $o['id'] ?>)">Hủy</button>
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
                <th class="text-end">Thành tiền</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($entry['items'] as $item):
                $size  = $item['variant_size']  ?? $item['size']  ?? '';
                $color = $item['variant_color'] ?? $item['color'] ?? '';
              ?>
                <tr>
                  <td>
                    <div class="fw-semibold"><?= htmlspecialchars($item['product_name']) ?></div>
                    <div class="small text-muted">Size: <?= htmlspecialchars($size) ?>; Màu: <?= htmlspecialchars($color) ?></div>
                  </td>
                  <td class="text-center"><?= $item['quantity'] ?></td>
                  <td class="text-end"><?= number_format($item['price'],0,',','.') ?>₫</td>
                  <td class="text-end"><?= number_format($item['quantity']*$item['price'],0,',','.') ?>₫</td>
                </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot class="table-light">
              <tr>
                <td colspan="2" class="align-middle">
                  <?php if ($status === 'pending'): ?>
                    <button class="btn btn-link btn-sm p-0 me-2" onclick="toggleEdit(<?= $o['id'] ?>)">
                      Chỉnh sửa địa chỉ
                    </button>
                    <form action="<?= BASE_URL ?>?action=cancel_order" method="post" class="d-inline">
                      <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                      <button type="submit" class="btn btn-danger btn-sm">Hủy đơn</button>
                    </form>
                  <?php endif; ?>
                </td>
                <th colspan="2" class="text-end">Tổng đơn: <?= number_format($o['total'],0,',','.') ?>₫</th>
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
function toggleEdit(orderId) {
  const form = document.getElementById(`addr-form-${orderId}`);
  const disp = document.getElementById(`addr-display-${orderId}`);
  form.classList.toggle('d-none');
  disp.closest('div').querySelector('button.btn-link').classList.toggle('d-none');
}

document.addEventListener('DOMContentLoaded', () => {
  const form      = document.getElementById('profile-form');
  const inputs    = form.querySelectorAll('input[name], textarea[name]');
  const btnEdit   = document.getElementById('btn-edit');
  const btnSave   = document.getElementById('btn-save');
  const btnCancel = document.getElementById('btn-cancel');

  function setEditable(editable) {
    inputs.forEach(el => el.disabled = !editable);
    btnEdit.classList.toggle('d-none', editable);
    btnSave.classList.toggle('d-none', !editable);
    btnCancel.classList.toggle('d-none', !editable);
  }

  btnEdit.addEventListener('click', () => setEditable(true));
  btnCancel.addEventListener('click', () => window.location.reload());
  setEditable(false);
});
</script>
