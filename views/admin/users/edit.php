<form method="post" action="<?= BASE_URL_ADMIN . '&action=users-update&id=' . $user['id'] ?>">
    <div class="mb-3">
        <label>Tên:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" class="form-control">
    </div>
    <div class="mb-3">
        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control">
    </div>
    <div class="mb-3">
        <label>Mật khẩu mới (bỏ trống nếu không đổi):</label>
        <input type="password" name="password" class="form-control">
    </div>
    <div class="mb-3">
        <label>Role:</label>
        <select name="role" class="form-control">
            <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Cập nhật</button>
    <a href="<?= BASE_URL_ADMIN . '&action=users-index' ?>" class="btn btn-danger">Quay lại</a>
</form>