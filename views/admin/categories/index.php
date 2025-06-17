<h2 class="mb-4">Danh sách danh mục</h2>

<a href="index.php?action=categories-create" class="btn btn-success mb-3">Thêm danh mục</a>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">Thao tác thành công!</div>
<?php endif ?>

<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>STT</th>
                <th>Tên danh mục</th>
                <th>Ảnh</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $i => $category): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= $category['name'] ?></td>
                    <td>
                        <?php if (!empty($category['image_url'])): ?>
                            <img src="<?= $category['image_url'] ?>" alt="Category Image" class="img-thumbnail" style="width: 100px;">
                        <?php endif ?>
                    </td>
                    <td>
                        <a href="index.php?action=categories-edit&id=<?= $category['id'] ?>" class="btn btn-sm btn-primary">Sửa</a>
                        <a href="index.php?action=categories-delete&id=<?= $category['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa danh mục này?')">Xóa</a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
