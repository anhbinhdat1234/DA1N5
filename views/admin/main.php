<?php require_once PATH_VIEW_ADMIN . 'layout/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <aside class="col-md-2 bg-dark text-white p-0">
            <div class="d-flex flex-column min-vh-100">
                
                <ul class="nav flex-column p-3">
                    <li class="nav-item">
                        <!-- Liên kết đến trang chủ dashboard -->
                        <a class="nav-link text-white" href="<?= BASE_URL_ADMIN ?>">🏠 Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <!-- Liên kết đến trang quản lý sản phẩm -->
                        <a class="nav-link text-white" href="<?= BASE_URL_ADMIN . '&action=sliders-index' ?>">📦 Quản lý Banner</a>
                    </li>
                    <li class="nav-item">
                        <!-- Liên kết đến trang quản lý sản phẩm -->
                        <a class="nav-link text-white" href="<?= BASE_URL_ADMIN . '&action=categories-index' ?>">📦 Quản lý Danh mục</a>
                    </li>
                    <li class="nav-item">
                        <!-- Liên kết đến trang quản lý sản phẩm -->
                        <a class="nav-link text-white" href="<?= BASE_URL_ADMIN . '&action=product-index' ?>">📦 Quản lý Sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <!-- Liên kết đến trang quản lý người dùng -->
                        <a class="nav-link text-white" href="<?= BASE_URL_ADMIN . '&action=users-index' ?>">👥 Quản lý Người dùng</a>
                    </li>
                    <li class="nav-item">
                        <!-- Liên kết đến trang quản lý đơn hàng -->
                        <a class="nav-link text-white" href="<?= BASE_URL_ADMIN . '&action=orders-index' ?>">🧾 Quản lý Đơn hàng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= BASE_URL_ADMIN . '&action=review-index' ?>">💬 Quản lý Bình luận</a>
                    </li>
                    <li class="nav-item mt-auto border-top pt-3">
                        <!-- Liên kết trở về trang client -->
                        <a class="nav-link text-white" href="<?= BASE_URL ?>">🌐 Về trang Client</a>
                    </li>
                    <li class="nav-item">
                        <!-- Liên kết đăng xuất với xác nhận -->
                        <a class="nav-link text-danger" href="<?= BASE_URL_ADMIN . '&action=logout' ?>" onclick="return confirm('Bạn chắc chắn muốn đăng xuất?')">🚪 Đăng xuất</a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main content -->
        <main class="col-md-10 bg-light p-5">
            <h1 class="mb-4 text-primary text-center">
                <?= $title ?? 'Quản trị hệ thống' ?>
            </h1>

            <?php if (isset($view)) : ?>
                <?php require_once PATH_VIEW_ADMIN . $view . '.php'; ?>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php require_once PATH_VIEW_ADMIN . 'layout/footer.php'; ?>