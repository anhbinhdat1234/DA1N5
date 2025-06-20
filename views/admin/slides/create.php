<?php require_once __DIR__ . '/../layout/header.php'; ?>
<?php require_once __DIR__ . '/../layout/sidebar.php'; ?>
<h2 class="mb-4"><?= isset($slider) ? 'Cập nhật Banner' : 'Thêm Banner mới' ?></h2>

<form method="POST" enctype="multipart/form-data" action="index.php?mode=admin&action=<?= isset($slider) ? 'sliders-update&id=' . $slider['id'] : 'sliders-store' ?>">

    <div class="mb-3">
        <label for="title" class="form-label">Tiêu đề</label>
        <input type="text" class="form-control" id="title" name="title" value="<?= $slider['title'] ?? '' ?>" required>
    </div>

    <div class="mb-3">
        <label for="subtitle" class="form-label">Phụ đề</label>
        <input type="text" class="form-control" id="subtitle" name="subtitle" value="<?= $slider['subtitle'] ?? '' ?>">
    </div>

    <div class="mb-3">
        <label for="link" class="form-label">Liên kết</label>
        <input type="text" class="form-control" id="link" name="link" value="<?= $slider['link'] ?? '' ?>">
    </div>

    <div class="mb-3">
        <label for="image_url" class="form-label">Ảnh</label><br>
        <?php if (!empty($slider['image_url'])): ?>
            <img src="<?= $slider['image_url'] ?>" alt="Ảnh hiện tại" class="img-thumbnail mb-2" width="150"><br>
            <!-- GIỮ ẢNH CŨ -->
            <input type="hidden" name="old_image_url" value="<?= $slider['image_url'] ?>">
        <?php endif ?>
        <input class="form-control" type="file" name="image_url" id="image_url">
    </div>

    <div class="mb-3">
        <label for="sort_order" class="form-label">Thứ tự hiển thị</label>
        <input type="number" class="form-control" id="sort_order" name="sort_order" value="<?= $slider['sort_order'] ?? 0 ?>">
    </div>

    <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-success">💾 Lưu</button>
        <a href="<?= BASE_URL_ADMIN ?>&action=sliders-index" class="btn btn-secondary">⬅ Quay lại</a>
    </div>
</form>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>