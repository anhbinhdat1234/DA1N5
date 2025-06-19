<aside class="col-md-2 bg-dark text-white p-0">
    <div class="d-flex flex-column min-vh-100">
        <ul class="nav flex-column p-3">
            <li class="nav-item">
                <a class="nav-link text-white" href="<?= BASE_URL_ADMIN ?>">🏠 Trang chủ</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?= BASE_URL_ADMIN . '&action=sliders-index' ?>">🖼️ Quản lý Banner</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?= BASE_URL_ADMIN . '&action=categories-index' ?>">📂 Quản lý Danh mục</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?= BASE_URL_ADMIN . '&action=product-index' ?>">📦 Quản lý Sản phẩm</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?= BASE_URL_ADMIN . '&action=users-index' ?>">👥 Quản lý Người dùng</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?= BASE_URL_ADMIN . '&action=orders-index' ?>">🧾 Quản lý Đơn hàng</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?= BASE_URL_ADMIN . '&action=review-index' ?>">💬 Quản lý Bình luận</a>
            </li>
            <li class="nav-item mt-auto border-top pt-3">
                <a class="nav-link text-white" href="<?= BASE_URL ?>">🌐 Về trang Client</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger" href="<?= BASE_URL_ADMIN . '&action=logout' ?>" onclick="return confirm('Bạn chắc chắn muốn đăng xuất?')">🚪 Đăng xuất</a>
            </li>
        </ul>
    </div>
</aside>

<main class="col-md-10 p-4">
