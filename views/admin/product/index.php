<?php

/*** views/admin/product/index.php */ ?>

<?php
// Log lỗi nếu biến $products chưa tồn tại hoặc không phải mảng
if (!isset($products) || !is_array($products)) {
    error_log("LỖI: Biến \$products không tồn tại hoặc không phải mảng ở index.php");
    echo '<div class="alert alert-danger">LỖI: Không có dữ liệu sản phẩm!</div>';
    $products = [];
}

// Log số lượng sản phẩm để kiểm tra
error_log("Tổng số sản phẩm: " . count($products));
?>

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
            <?php
            // Log từng sản phẩm (nếu muốn debug sâu hơn)
            error_log("SP: " . json_encode($p));
            ?>
            <tr>
                <td><?= isset($p['id']) ? $p['id'] : '<span style="color:red">Không có ID</span>' ?></td>
                <td>
                    <div style="font-size:12px;color:#888;">
                        image_url: <?= var_export($p['image_url'] ?? null, true) ?><br>
                        <a href="<?= $p['image_url'] ?>" target="_blank"><?= $p['image_url'] ?></a>
                    </div>
                    <?php if (!empty($p['image_url'])): ?>
                        <img src="<?= $p['image_url'] ?>" width="60">
                    <?php else: ?>
                        <span style="color:orange">No Image</span>
                    <?php endif; ?>
                </td>







                <td><?= isset($p['name']) ? htmlspecialchars($p['name']) : '<span style="color:red">Không tên</span>' ?></td>
                <td><?= isset($p['price']) ? number_format($p['price']) . ' đ' : '<span style="color:red">Không giá</span>' ?></td>
                <td><?= isset($p['category_name']) ? $p['category_name'] : '<span style="color:red">Không có danh mục</span>' ?></td>
                <td>
                    <a href="<?= BASE_URL_ADMIN . '&action=product-show&id=' . $p['id'] ?>" class="btn btn-info btn-sm">Xem</a>
                    <a href="<?= BASE_URL_ADMIN . '&action=product-edit&id=' . $p['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                    <a href="<?= BASE_URL_ADMIN . '&action=product-delete&id=' . $p['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Xác nhận xoá?')">Xóa</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>