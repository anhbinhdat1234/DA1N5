<?php /*** views/admin/product/index.php */ ?>
<a href="<?= BASE_URL_ADMIN . '&action=product-create' ?>" class="btn btn-primary mb-3">Thêm sản phẩm</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Hình</th>
            <th>Tên</th>
            <th>Giá</th>
            <th>Danh mục</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($products as $p): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td><img src="<?= $p['image_url'] ?>" width="60"></td>
            <td><?= htmlspecialchars($p['name']) ?></td>
            <td><?= number_format($p['price']) ?> đ</td>
            <td><?= $p['category_name'] ?></td>
            <td>
                <a href="<?= BASE_URL_ADMIN . '&action=product-show&id=' . $p['id'] ?>" class="btn btn-info btn-sm">Xem</a>
                <a href="<?= BASE_URL_ADMIN . '&action=product-edit&id=' . $p['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                <a href="<?= BASE_URL_ADMIN . '&action=product-delete&id=' . $p['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Xác nhận xoá?')">Xóa</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>