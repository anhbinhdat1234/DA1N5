<?php require_once __DIR__ . '/../layout/header.php'; ?>
<?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Danh sách Banner</h2>
        <a href="<?= BASE_URL_ADMIN ?>&action=sliders-create" class="btn btn-primary">Thêm mới</a>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tiêu đề</th>
                <th>Ảnh</th>
                <th>Thứ tự</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sliders as $slider): ?>
                <tr>
                    <td><?= $slider['id'] ?></td>
                    <td><?= htmlspecialchars($slider['title']) ?></td>
                    <td>
                        <img src="<?= BASE_URL . '/' . $slider['image_url'] ?>" alt="Slider Image" width="100" class="img-thumbnail">

                    </td>
                    <td><?= $slider['sort_order'] ?></td>
                    <td>
                        <a href="index.php?mode=admin&action=sliders-edit&id=<?= $slider['id'] ?>" class="btn btn-sm btn-warning">Sửa</a>
                        <a href="index.php?mode=admin&action=sliders-delete&id=<?= $slider['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa banner này?')">Xóa</a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>