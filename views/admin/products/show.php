<?php /*** views/admin/product/show.php */ ?>
<table class="table">
    <tr><th>ID</th><td><?= $product['id'] ?></td></tr>
    <tr><th>Tên</th><td><?= $product['name'] ?></td></tr>
    <tr><th>Giá</th><td><?= number_format($product['price']) ?> đ</td></tr>
    <tr><th>Mô tả</th><td><?= $product['description'] ?></td></tr>
    <tr><th>Danh mục</th><td><?= $product['category_name'] ?></td></tr>
    <tr><th>Hình</th>
        <td>
        <?php foreach ($product['images'] as $img): ?>
            <img src="<?= $img ?>" width="80">
        <?php endforeach; ?>
        </td>
    </tr>
</table>
<a href="<?= BASE_URL_ADMIN . '&action=product-index' ?>" class="btn btn-secondary">Quay lại</a>