<a href="<?= BASE_URL_ADMIN . '&action=users-create' ?>" class="btn btn-primary mb-3">Thêm mới</a>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert <?= $_SESSION['success'] ? 'alert-success' : 'alert-danger' ?>">
        <?= $_SESSION['msg'] ?>
    </div>
    <?php unset($_SESSION['success'], $_SESSION['msg']); ?>
<?php endif; ?>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Email</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['name']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td>
                    <a href="<?= BASE_URL_ADMIN . '&action=users-show&id=' . $user['id'] ?>" class="btn btn-info btn-sm">Xem</a>
                    <a href="<?= BASE_URL_ADMIN . '&action=users-edit&id=' . $user['id'] ?>" class="btn btn-warning btn-sm ms-2">Sửa</a>
                    <a href="<?= BASE_URL_ADMIN . '&action=users-delete&id=' . $user['id'] ?>" class="btn btn-danger btn-sm ms-2" onclick="return confirm('Có chắc xóa không?')">Xóa</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>