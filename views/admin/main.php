<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $title ?? 'Admin Dashboard' ?></title>

    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <nav class="navbar navbar-expand-xxl bg-light justify-content-center">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link text-uppercase" href="<?= BASE_URL_ADMIN ?>"><b>Dashboard</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-uppercase" href="<?= BASE_URL_ADMIN . '&action=users-index' ?>"><b>Quản lý User</b></a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-uppercase" href="<?= BASE_URL_ADMIN . '&action=books-index' ?>"><b>Quản lý Book</b></a>
            </li>

            <?php if (!empty($_SESSION['user'])): ?>
                <li class="nav-item">
                    <a class="nav-link text-uppercase text-danger"
                        href="<?= BASE_URL_ADMIN . '&action=logout' ?>"
                        onclick="return confirm('Có chắc chắn đăng xuất?')"> <b>Đăng xuất</b> </a>
                </li>
            <?php endif; ?>

        </ul>
    </nav>

    <div class="container">
        <h1 class="mt-3 mb-3"><?= $title ?? 'Admin Dashboard' ?></h1>

        <div class="row">
            <?php
            if (isset($view)) {
                require_once PATH_VIEW_ADMIN . $view . '.php';
            }
            ?>
        </div>
    </div>

</body>

</html>