<h2>Danh sách Banner</h2>
<a href="index.php?controller=slider&action=create" class="btn btn-success mb-3">Thêm Banner</a>


<table class="table table-bordered">
    <thead>
        <tr>
            <th>Ảnh</th>
            <th>Tiêu đề</th>
            <th>Phụ đề</th>
            <th>Link</th>
            <th>Thứ tự</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($sliders as $item): ?>
            <tr>
                <td><img src="<?= $item['image_url'] ?>" width="100"></td>
                <td><?= $item['title'] ?></td>
                <td><?= $item['subtitle'] ?></td>
                <td><?= $item['link'] ?></td>
                <td><?= $item['sort_order'] ?></td>
                <td>
                    <a href="index.php?controller=slider&action=edit&id=<?= $item['id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                    <a href="index.php?controller=slider&action=delete&id=<?= $item['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xoá?')">Xoá</a>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
