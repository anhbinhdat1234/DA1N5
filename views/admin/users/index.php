<a href="<?= BASE_URL_ADMIN . '&action=users-create' ?>" class="btn btn-primary mb-3">Thêm mới</a>

<?php
if (isset($_SESSION['success'])) {
    $class = $_SESSION['success'] ? 'alert-success' : 'alert-danger';

    echo "<div class='alert $class'> {$_SESSION['msg']} </div>";

    unset($_SESSION['success']);
    unset($_SESSION['msg']);
}
?>

<table class="table">
    <tr>
        <th>ID</th>
        <th>AVATAR</th>
        <th>NAME</th>
        <th>EMAIL</th>
        <th>ACTION</th>
    </tr>

    <?php foreach ($data as $user): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td>
                <?php if (!empty($user['avatar'])): ?>
                    <img src="<?= BASE_ASSETS_UPLOADS . $user['avatar'] ?>" width="100px">
                <?php endif; ?>
            </td>
            <td><?= $user['name'] ?></td>
            <td><?= $user['email'] ?></td>
            <td>
                <a href="<?= BASE_URL_ADMIN . '&action=users-show&id=' . $user['id'] ?>"
                    class="btn btn-info">Xem</a>

                <a href="<?= BASE_URL_ADMIN . '&action=users-edit&id=' . $user['id'] ?>"
                    class="btn btn-warning ms-3 me-3">Sửa</a>

                <a href="<?= BASE_URL_ADMIN . '&action=users-delete&id=' . $user['id'] ?>"
                    onclick="return confirm('Có chắc xóa không?')"
                    class="btn btn-danger">Xóa</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>