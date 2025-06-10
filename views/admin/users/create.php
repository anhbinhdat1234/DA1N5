<?php
if (isset($_SESSION['success'])) {
    $class = $_SESSION['success'] ? 'alert-success' : 'alert-danger';
    echo "<div class='alert $class'>{$_SESSION['msg']}</div>";
    unset($_SESSION['success'], $_SESSION['msg']);
}
?>

<?php if (!empty($_SESSION['errors'])): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($_SESSION['errors'] as $value): ?>
                <li><?= $value ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php unset($_SESSION['errors']); ?>
<?php endif; ?>

<form action="<?= BASE_URL_ADMIN . '&action=users-store' ?>" method="post">
    <div class="mb-3 mt-3">
        <label for="name" class="form-label">Tên:</label>
        <input type="text" class="form-control" id="name" name="name"
            value="<?= htmlspecialchars($_SESSION['data']['name'] ?? '') ?>">
    </div>
    <div class="mb-3 mt-3">
        <label for="email" class="form-label">Email:</label>
        <input type="email" class="form-control" id="email" name="email"
            value="<?= htmlspecialchars($_SESSION['data']['email'] ?? '') ?>">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Mật khẩu:</label>
        <input type="password" class="form-control" id="password" name="password" autocomplete="new-password">
    </div>
    <div class="mb-3">
        <label for="role" class="form-label">Quyền:</label>
        <select class="form-control" id="role" name="role">
            <option value="user"
                <?= (($_SESSION['data']['role'] ?? '') === 'user') ? 'selected' : '' ?>>
                User
            </option>
            <option value="admin"
                <?= (($_SESSION['data']['role'] ?? '') === 'admin') ? 'selected' : '' ?>>
                Admin
            </option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Thêm mới</button>
    <a href="<?= BASE_URL_ADMIN . '&action=users-index' ?>" class="btn btn-danger">Quay lại danh sách</a>
</form>

<?php unset($_SESSION['data']); ?>