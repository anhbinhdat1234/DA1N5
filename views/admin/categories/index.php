<?php require_once __DIR__ . '/../layout/header.php'; ?>
<?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

<div class="mx-auto" style="width: 98%;">
    <h2 class="mb-4">Danh sách danh mục</h2>

<a href="<?= BASE_URL_ADMIN ?>&action=categories-create" class="btn btn-success mb-3" >+ Thêm danh mục</a>
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">✅ Thao tác thành công!</div>
    <?php endif ?>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Tên danh mục</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $index => $category): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($category['name']) ?></td>
                            
                            <td>
<a href="<?= BASE_URL_ADMIN ?>&action=categories-edit&id=<?= $category['id'] ?>" class="btn btn-sm btn-primary">Sửa</a>
<a href="<?= BASE_URL_ADMIN ?>&action=categories-delete&id=<?= $category['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xoá?')">Xoá</a>

                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Không có danh mục nào.</td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
