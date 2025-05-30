<?php
if (isset($_SESSION['success'])) {
    $class = $_SESSION['success'] ? 'alert-success' : 'alert-danger';

    echo "<div class='alert $class'> {$_SESSION['msg']} </div>";

    unset($_SESSION['success']);
    unset($_SESSION['msg']);
}
?>

<?php if (!empty($_SESSION['errors'])): ?>

<div class="alert alert-danger">

    <ul>
        <?php foreach ($_SESSION['errors'] as $value): ?>

            <li> <?= $value ?> </li>

        <?php endforeach; ?>
    </ul>
    
</div>

<?php unset($_SESSION['errors']); ?>

<?php endif; ?>


<form action="<?= BASE_URL_ADMIN . '&action=users-update&id=' . $user['id'] ?>" method="post" enctype="multipart/form-data">
    <div class="mb-3 mt-3">
        <label for="name" class="form-label">Name:</label>
        <input type="text" class="form-control" id="name" name="name" value="<?= $user['name'] ?>">
    </div>
    <div class="mb-3 mt-3">
        <label for="email" class="form-label">Email:</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?>">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password:</label>
        <input type="text" class="form-control" id="password" name="password" value="<?= $user['password'] ?>">
    </div>
    <div class="mb-3">
        <label for="avatar" class="form-label">Avatar:</label>
        <input type="file" class="form-control" id="avatar" name="avatar">

        <?php if (!empty($user['avatar'])): ?>
            <img src="<?= BASE_ASSETS_UPLOADS . $user['avatar'] ?>" width="100px">
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>

    <a href="<?= BASE_URL_ADMIN . '&action=users-index' ?>" class="btn btn-danger">Quay lại danh sách</a>
</form>