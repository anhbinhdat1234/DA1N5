<?php /*** views/admin/product/show.php */ ?>

<h2>Chi tiết sản phẩm #<?= $product['id'] ?></h2>

<table class="table table-striped">
    <tr><th>ID</th>        <td><?= $product['id'] ?></td></tr>
    <tr><th>Tên</th>      <td><?= htmlspecialchars($product['name']) ?></td></tr>
    <tr><th>Giá</th>      <td><?= number_format($product['price'],0,',','.') ?>₫</td></tr>
    <tr><th>Mô tả</th>    <td><?= nl2br(htmlspecialchars($product['description'])) ?></td></tr>
    <tr><th>Danh mục</th> <td><?= htmlspecialchars($product['category_name']) ?></td></tr>
    <tr>
      <th>Ảnh</th>
      <td class="d-flex flex-wrap gap-2">
        <?php if (!empty($product['images'])): ?>
          <?php foreach ($product['images'] as $img): ?>
            <img src="<?= BASE_URL . $img ?>"
                 class="img-thumbnail"
                 style="width:100px; height:auto;">
          <?php endforeach; ?>
        <?php else: ?>
          <span class="text-muted">Chưa có ảnh</span>
        <?php endif; ?>
      </td>
    </tr>
</table>

<a href="<?= BASE_URL_ADMIN . '&action=product-index' ?>" class="btn btn-secondary">
  <i class="bi bi-arrow-left-circle"></i> Quay lại
</a>
