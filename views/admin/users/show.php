<!-- ...thông báo... -->
<a href="<?= BASE_URL_ADMIN . '&action=users-create' ?>" class="btn btn-success mb-2">Thêm User</a>
<table class="table">
    <tr>
        <th>ID</th>
        <td><?= $user['id'] ?></td>
    </tr>
    <tr>
        <th>Name</th>
        <td><?= htmlspecialchars($user['name']) ?></td>
    </tr>
    <tr>
        <th>Email</th>
        <td><?= htmlspecialchars($user['email']) ?></td>
    </tr>
    <tr>
        <th>Phone</th>
        <td><?= htmlspecialchars($user['phone'] ?? '') ?></td>
    </tr>
    <tr>
        <th>Address</th>
        <td><?= htmlspecialchars($user['address'] ?? '') ?></td>
    </tr>
    <tr>
        <th>Role</th>
        <td><?= htmlspecialchars($user['role']) ?></td>
    </tr>
    <tr>
        <th>Created At</th>
        <td><?= $user['created_at'] ?></td>
    </tr>
</table>
<a href="<?= BASE_URL_ADMIN . '&action=users-index' ?>" class="btn btn-danger">Quay lại</a>