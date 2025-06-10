<?php /*** views/admin/product/edit.php */ ?>
<form method="post" action="<?= BASE_URL_ADMIN . '&action=product-update&id=' . $product['id'] ?>">
    <div class="mb-3">
        <label>Tên</label>
        <input type="text" name="name" class="form-control" value="<?= $product['name'] ?>">
    </div>
    <div class="mb-3">
        <label>Giá</label>
        <input type="number" name="price" class="form-control" value="<?= $product['price'] ?>">
    </div>
    <div class="mb-3">
        <label>Mô tả</label>
        <textarea name="description" class="form-control"><?= $product['description'] ?></textarea>
    </div>
    <div class="mb-3">
        <label>Danh mục</label>
        <select name="category_id" class="form-select">
            <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $c['id'] == $product['category_id'] ? 'selected' : '' ?>><?= $c['name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button class="btn btn-primary">Cập nhật</button>
</form>