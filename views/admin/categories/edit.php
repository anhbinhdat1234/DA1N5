<?php require_once __DIR__ . '/../layout/header.php'; ?>
<?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

<div class="container mt-4">
    <h2>Cập nhật danh mục</h2>

    <form method="POST" action="<?= BASE_URL_ADMIN ?>&action=categories-update&id=<?= $category['id'] ?>" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Tên danh mục</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($category['name']) ?>" class="form-control" required>
        </div>

       

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="<?= BASE_URL_ADMIN ?>&action=categories-index" class="btn btn-secondary">Quay lại</a>
    </form>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
