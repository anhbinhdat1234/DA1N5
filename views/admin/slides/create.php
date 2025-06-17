<?php require_once PATH_VIEW_ADMIN . 'layout/header.php'; ?>

<div class="container my-4">
    <h2><?= isset($slider) ? 'Cập nhật Slider' : 'Thêm Slider mới' ?></h2>

    <form method="POST" enctype="multipart/form-data"
        action="index.php?controller=slider&action=<?= isset($slider) ? 'update&id=' . $slider['id'] : 'store' ?>">
        
        <div class="form-group mb-3">
            <label>Tiêu đề</label>
            <input type="text" name="title" class="form-control" value="<?= $slider['title'] ?? '' ?>" required>
        </div>

        <div class="form-group mb-3">
            <label>Phụ đề</label>
            <input type="text" name="subtitle" class="form-control" value="<?= $slider['subtitle'] ?? '' ?>">
        </div>

        <div class="form-group mb-3">
            <label>Link</label>
            <input type="text" name="link" class="form-control" value="<?= $slider['link'] ?? '' ?>">
        </div>

        <div class="form-group mb-3">
            <label>Thứ tự hiển thị</label>
            <input type="number" name="sort_order" class="form-control" value="<?= $slider['sort_order'] ?? 0 ?>">
        </div>

        <div class="form-group mb-3">
            <label>Ảnh</label>
            <input type="file" name="image_url" class="form-control">
            <?php if (!empty($slider['image_url'])): ?>
                <img src="<?= $slider['image_url'] ?>" width="150" class="mt-2 border rounded">
            <?php endif ?>
        </div>

        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="index.php?controller=slider&action=index" class="btn btn-secondary">Quay lại</a>
    </form>
</div>

<?php require_once PATH_VIEW_ADMIN . 'layout/footer.php'; ?>
