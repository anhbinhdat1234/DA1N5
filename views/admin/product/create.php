<?php /*** views/admin/product/create.php */ ?>
<h2>Thêm sản phẩm mới</h2>

<?php if (!empty($_SESSION['errors'])): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php foreach ($_SESSION['errors'] as $err): ?>
        <li><?= htmlspecialchars($err) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php unset($_SESSION['errors']); ?>
<?php endif; ?>

<form method="post"
      action="<?= BASE_URL_ADMIN . '&action=product-store' ?>"
      enctype="multipart/form-data">
    <div class="mb-3">
        <label class="form-label">Tên sản phẩm</label>
        <input type="text" name="name" class="form-control"
               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Giá</label>
        <input type="number" name="price" class="form-control"
               value="<?= htmlspecialchars($_POST['price'] ?? '') ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Mô tả</label>
        <textarea name="description" class="form-control"
                  rows="4"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Danh mục</label>
        <select name="category_id" class="form-select">
            <option value="">-- Chọn danh mục --</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>"
                  <?= (($_POST['category_id'] ?? '') == $c['id']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($c['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Ảnh sản phẩm</label>
        <input type="file" name="images[]" multiple accept="image/*" class="form-control">
        <small class="text-muted">Chọn 1 hoặc nhiều ảnh (jpg/png/gif).</small>
    </div>

    <button type="submit" class="btn btn-success">Lưu</button>
    <a href="<?= BASE_URL_ADMIN . '&action=product-index' ?>" class="btn btn-secondary">Hủy</a>
</form>
