<?php /*** views/admin/product/show.php ***/ ?>
<h2>Chi tiết sản phẩm #<?= htmlspecialchars($product['id']) ?></h2>

<table class="table table-striped">
  <tr><th>ID</th>        <td><?= htmlspecialchars($product['id']) ?></td></tr>
  <tr><th>Tên</th>      <td><?= htmlspecialchars($product['name']) ?></td></tr>
  <tr><th>Giá</th>      <td><?= number_format($product['price'],0,',','.') ?>₫</td></tr>
  <tr><th>Danh mục</th> <td><?= htmlspecialchars($product['category_name']) ?></td></tr>
  <tr><th>Mô tả</th>    <td><?= nl2br(htmlspecialchars($product['description'])) ?></td></tr>
  <tr>
    <th>Ảnh</th>
    <td class="d-flex flex-wrap gap-2">
      <?php if ($images): ?>
        <?php foreach ($images as $img): ?>
          <?php
            $url = filter_var($img['image_url'], FILTER_VALIDATE_URL)
                 ? $img['image_url']
                 : BASE_URL . ltrim($img['image_url'], '/');
          ?>
          <img src="<?= htmlspecialchars($url) ?>"
               class="img-thumbnail" style="width:120px;">
        <?php endforeach; ?>
      <?php else: ?>
        <span class="text-muted">Chưa có ảnh</span>
      <?php endif; ?>
    </td>
  </tr>
  <tr><th>Ngày tạo</th>  <td><?= htmlspecialchars($product['created_at']) ?></td></tr>
  <?php if (!empty($product['updated_at'])): ?>
  <tr><th>Ngày cập nhật</th><td><?= htmlspecialchars($product['updated_at']) ?></td></tr>
  <?php endif; ?>
</table>

<a href="<?= BASE_URL_ADMIN . '&action=product-index' ?>" class="btn btn-secondary">Quay lại</a>
<a href="<?= BASE_URL_ADMIN . '&action=product-edit&id=' . $product['id'] ?>"
   class="btn btn-warning">Sửa</a>
