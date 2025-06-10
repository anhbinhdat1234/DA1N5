<?php /*** views/admin/product/create.php */ ?>
<form method="post" action="<?= BASE_URL_ADMIN . '&action=product-store' ?>">
    <div class="mb-3">
        <label>Tên sản phẩm</label>
        <input type="text" name="name" class="form-control">
    </div>
    <div class="mb-3">
        <label>Giá</label>
        <input type="number" name="price" class="form-control">
    </div>
    <div class="mb-3">
        <label>Mô tả</label>
        <textarea name="description" class="form-control"></textarea>
    </div>
    <div class="mb-3">
        <label>Danh mục</label>
        <select name="category_id" class="form-select">
            <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button class="btn btn-success">Lưu</button>
</form>