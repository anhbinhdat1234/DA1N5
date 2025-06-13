<?php /*** views/admin/product/create.php ***/ ?>

<h2>Thêm sản phẩm mới</h2>
<form method="post" action="<?= BASE_URL_ADMIN ?>&action=product-store"
      enctype="multipart/form-data">

  <!-- Tên -->
  <div class="mb-3">
    <label class="form-label">Tên sản phẩm</label>
    <input type="text"
           name="name"
           class="form-control <?= isset($_SESSION['errors']['name'])?'is-invalid':'' ?>"
           value="<?= htmlspecialchars($_SESSION['old']['name']??'') ?>">
    <?php if(isset($_SESSION['errors']['name'])):?>
      <div class="invalid-feedback">
        <?= htmlspecialchars($_SESSION['errors']['name']) ?>
      </div>
    <?php endif;?>
  </div>

  <!-- Giá -->
  <div class="mb-3">
    <label class="form-label">Giá (VNĐ)</label>
    <input type="text"
           name="price"
           class="form-control <?= isset($_SESSION['errors']['price'])?'is-invalid':'' ?>"
           value="<?= htmlspecialchars($_SESSION['old']['price']??'') ?>">
    <?php if(isset($_SESSION['errors']['price'])):?>
      <div class="invalid-feedback">
        <?= htmlspecialchars($_SESSION['errors']['price']) ?>
      </div>
    <?php endif;?>
  </div>

  <!-- Mô tả -->
  <div class="mb-3">
    <label class="form-label">Mô tả</label>
    <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($_SESSION['old']['description']??'') ?></textarea>
  </div>

  <!-- Danh mục -->
  <div class="mb-3">
    <label class="form-label">Danh mục</label>
    <select name="category_id" class="form-select <?= isset($_SESSION['errors']['category_id'])?'is-invalid':'' ?>">
      <option value="">-- Chọn --</option>
      <?php foreach($categories as $c): ?>
        <option value="<?= $c['id'] ?>"
          <?= (($_SESSION['old']['category_id']??'')==$c['id'])?'selected':'' ?>>
          <?= htmlspecialchars($c['name']) ?>
        </option>
      <?php endforeach;?>
    </select>
    <?php if(isset($_SESSION['errors']['category_id'])):?>
      <div class="invalid-feedback">
        <?= htmlspecialchars($_SESSION['errors']['category_id']) ?>
      </div>
    <?php endif;?>
  </div>

  <!-- Upload ảnh -->
  <div class="mb-3">
    <label class="form-label">Upload ảnh (file)</label>
    <input type="file" name="images[]" multiple accept="image/*" class="form-control">
    <small class="text-muted">Chọn file JPG/PNG/GIF.</small>
  </div>

  <!-- Link hoặc data URI -->
  <div class="mb-3">
    <label class="form-label">Link ảnh hoặc data URI (mỗi dòng 1)</label>
    <textarea name="external_images" class="form-control" rows="3"
      placeholder="https://...jpg
data:image/png;base64,..."><?= htmlspecialchars($_SESSION['old']['external_images']??'') ?></textarea>
    <small class="text-muted">Hỗ trợ URL hoặc base64 data URI.</small>
  </div>

  <button class="btn btn-success">Lưu</button>
  <a href="<?= BASE_URL_ADMIN ?>&action=product-index" class="btn btn-secondary">Hủy</a>
</form>

<?php unset($_SESSION['errors'], $_SESSION['old']); ?>
