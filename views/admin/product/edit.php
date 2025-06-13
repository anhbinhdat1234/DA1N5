<?php /*** views/admin/product/edit.php ***/ ?>

<h2>Sửa sản phẩm #<?= $product['id'] ?></h2>
<form method="post"
      action="<?= BASE_URL_ADMIN ?>&action=product-update&id=<?= $product['id'] ?>"
      enctype="multipart/form-data">

  <!-- Tên -->
  <div class="mb-3">
    <label class="form-label">Tên sản phẩm</label>
    <input type="text"
           name="name"
           class="form-control <?= isset($_SESSION['errors']['name'])?'is-invalid':'' ?>"
           value="<?= htmlspecialchars($_SESSION['old']['name'] ?? $product['name']) ?>">
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
           value="<?= htmlspecialchars($_SESSION['old']['price'] ?? $product['price']) ?>">
    <?php if(isset($_SESSION['errors']['price'])):?>
      <div class="invalid-feedback">
        <?= htmlspecialchars($_SESSION['errors']['price']) ?>
      </div>
    <?php endif;?>
  </div>

  <!-- Mô tả -->
  <div class="mb-3">
    <label class="form-label">Mô tả</label>
    <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($_SESSION['old']['description'] ?? $product['description']) ?></textarea>
  </div>

  <!-- Danh mục -->
  <div class="mb-3">
    <label class="form-label">Danh mục</label>
    <select name="category_id" class="form-select <?= isset($_SESSION['errors']['category_id'])?'is-invalid':'' ?>">
      <?php $sel = $_SESSION['old']['category_id'] ?? $product['category_id']; ?>
      <?php foreach($categories as $c): ?>
        <option value="<?= $c['id'] ?>" <?= $sel==$c['id']?'selected':'' ?>>
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

  <!-- Ảnh hiện tại -->
  <div class="mb-3">
    <label class="form-label">Ảnh hiện tại</label>
    <div class="d-flex flex-wrap gap-2">
      <?php if($images): ?>
        <?php foreach($images as $img): ?>
          <?php 
            $src = filter_var($img['image_url'], FILTER_VALIDATE_URL)
                 ? $img['image_url']
                 : BASE_ASSETS_UPLOADS . ltrim($img['image_url'],'/');
          ?>
          <img src="<?= htmlspecialchars($src) ?>" class="img-thumbnail" style="width:100px;">
        <?php endforeach; ?>
      <?php else: ?>
        <span class="text-muted">Chưa có ảnh</span>
      <?php endif; ?>
    </div>
  </div>

  <!-- Upload thêm file -->
  <div class="mb-3">
    <label class="form-label">Upload thêm ảnh (file)</label>
    <input type="file" name="images[]" multiple accept="image/*" class="form-control">
  </div>

  <!-- Link/data URI -->
  <div class="mb-3">
    <label class="form-label">Link hoặc data URI (mỗi dòng 1)</label>
    <textarea name="external_images" class="form-control" rows="3"
      placeholder="https://...jpg
data:image/png;base64,..."><?= htmlspecialchars($_SESSION['old']['external_images'] ?? '') ?></textarea>
  </div>

  <button class="btn btn-primary">Cập nhật</button>
  <a href="<?= BASE_URL_ADMIN ?>&action=product-index" class="btn btn-secondary">Hủy</a>
</form>

<?php unset($_SESSION['errors'], $_SESSION['old']); ?>
