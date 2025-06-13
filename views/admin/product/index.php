<?php /*** chỉ phần nội dung riêng của “index” ***/ ?>

<a href="<?= BASE_URL_ADMIN ?>&action=product-create" class="btn btn-success mb-3">
  Thêm sản phẩm
</a>

<?php if (isset($_SESSION['success'])): ?>
  <div class="alert <?= $_SESSION['success'] ? 'alert-success' : 'alert-danger' ?>">
    <?= htmlspecialchars($_SESSION['msg']) ?>
  </div>
  <?php unset($_SESSION['success'], $_SESSION['msg']); ?>
<?php endif; ?>

<table class="table table-bordered align-middle">
  <thead>
    <tr>
      <th>Ảnh</th><th>ID</th><th>Tên</th><th>Giá</th><th>Danh mục</th><th>Thao tác</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($products as $p): ?>
      <tr>
        <td style="width:80px;">
          <?php if (!empty($p['image_url'])): ?>
            <img src="<?= BASE_URL . ltrim($p['image_url'],'/') ?>"
                 class="img-fluid rounded" width="60">
          <?php else: ?>
            <span class="text-muted">No Image</span>
          <?php endif; ?>
        </td>
        <td><?= $p['id'] ?></td>
        <td><?= htmlspecialchars($p['name']) ?></td>
        <td><?= number_format($p['price'],0,',','.') ?>₫</td>
        <td><?= htmlspecialchars($p['category_name']) ?></td>
        <td>
          <a href="<?= BASE_URL_ADMIN ?>&action=product-show&id=<?= $p['id'] ?>"
             class="btn btn-info btn-sm">Xem</a>
          <a href="<?= BASE_URL_ADMIN ?>&action=product-edit&id=<?= $p['id'] ?>"
             class="btn btn-warning btn-sm">Sửa</a>
          <a href="<?= BASE_URL_ADMIN ?>&action=product-delete&id=<?= $p['id'] ?>"
             class="btn btn-danger btn-sm"
             onclick="return confirm('Xác nhận xoá?')">Xóa</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
