<?php /*** views/admin/product/edit.php */ ?>
<h2>Sửa sản phẩm #<?= $product['id'] ?></h2>

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
      action="<?= BASE_URL_ADMIN . '&action=product-update&id=' . $product['id'] ?>"
      enctype="multipart/form-data">
    <div class="mb-3">
        <label class="form-label">Tên</label>
        <input type="text" name="name" class="form-control"
               value="<?= htmlspecialchars($product['name']) ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Giá</label>
        <input type="number" name="price" class="form-control"
               value="<?= htmlspecialchars($product['price']) ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Mô tả</label>
        <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($product['description']) ?></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Danh mục</label>
        <select name="category_id" class="form-select">
            <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>"
                  <?= ($c['id'] == $product['category_id']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($c['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-4">
      <label class="form-label">Ảnh hiện tại</label>
      <div class="d-flex flex-wrap gap-2">
        <?php if (!empty($productImages)): ?>
          <?php foreach ($productImages as $img): ?>
            <img src="<?= BASE_URL . $img['image_url'] ?>"
                 class="img-thumbnail" style="width:100px; height:auto;">
          <?php endforeach; ?>
        <?php else: ?>
          <span class="text-muted">Chưa có ảnh</span>
        <?php endif; ?>
      </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Thêm ảnh mới</label>
        <input type="file" name="images[]" multiple accept="image/*" class="form-control">
        <small class="text-muted">Upload ảnh để thêm vào gallery.</small>
    </div>

    <button type="submit" class="btn btn-primary">Cập nhật</button>
    <a href="<?= BASE_URL_ADMIN . '&action=product-index' ?>" class="btn btn-secondary">Hủy</a>
</form>
