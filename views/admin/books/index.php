<a href="<?= BASE_URL_ADMIN . '&action=books-create' ?>" class="btn btn-primary mb-3">Thêm mới</a>

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
        <th>IMG COVER</th>
        <th>TITLE</th>
        <th>AUTHOR</th>
        <th>CATEGORY NAME</th>
        <th>PUBLISHED YEAR</th>
        <th>CREATED AT</th>
        <th>UPDATED AT</th>
        <th>ACTION</th>
    </tr>

    <?php foreach ($data as $book): ?>
        <tr>
            <td><?= $book['b_id'] ?></td>
            <td>
                <?php if (!empty($book['b_img_cover'])): ?>
                    <img src="<?= BASE_ASSETS_UPLOADS . $book['b_img_cover'] ?>" width="100px">
                <?php endif; ?>
            </td>
            <td><?= $book['b_title'] ?></td>
            <td><?= $book['b_author'] ?></td>
            <td><?= $book['c_name'] ?></td>
            <td><?= $book['b_published_year'] ?></td>

            <td><?= date('d/m/Y H:i:s', strtotime($book['b_created_at'])) ?></td>
            
            <td><?= $book['b_updated_at'] ?></td>
            <td>
                <a href="<?= BASE_URL_ADMIN . '&action=books-show&id=' . $book['b_id'] ?>"
                    class="btn btn-info">Xem</a>

                <a href="<?= BASE_URL_ADMIN . '&action=books-edit&id=' . $book['b_id'] ?>"
                    class="btn btn-warning ms-1 me-1 mb-1 mt-1">Sửa</a>

                <a href="<?= BASE_URL_ADMIN . '&action=books-delete&id=' . $book['b_id'] ?>"
                    onclick="return confirm('Có chắc xóa không?')"
                    class="btn btn-danger">Xóa</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>