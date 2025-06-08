<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
	$cart = $_SESSION['cart'] ?? [];

	$cartItemCount = is_array($cart)
    ? array_sum($cart)
    : 0;
?>
<?php if (!empty($_SESSION['flash'])): ?>
  <div class="container mt-2">
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash']) ?></div>
  </div>
  <?php unset($_SESSION['flash']); ?>
<?php endif; ?>
<!DOCTYPE html>
<html lang="vi" data-bs-theme="light" data-pwa="true">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
	<meta charset="utf-8">
	<meta name="viewport"
		content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover">
	<title>Fashion Store</title>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<link rel="manifest" href="manifest.json">
	<link rel="icon" type="image/png" href="<?php echo BASE_URL ?>/assets/client/assets/app-icons/icon-32x32.png"
		sizes="32x32">
	<link rel="apple-touch-icon" href="<?php echo BASE_URL ?>/assets/client/assets/app-icons/icon-180x180.png">
	<script src="<?php echo BASE_URL ?>/assets/client/assets/js/theme-switcher.js"></script>
	<link rel="preload" href="<?php echo BASE_URL ?>/assets/client/assets/fonts/inter-variable-latin.woff2" as="font"
		type="font/woff2" crossorigin="">
	<link rel="preload" href="<?php echo BASE_URL ?>assets/client/assets/icons/cartzilla-icons.woff2" as="font"
		type="font/woff2" crossorigin="">
	<link rel="stylesheet" href="<?php echo BASE_URL ?>assets/client/assets/icons/cartzilla-icons.min.css">
	<link rel="stylesheet" href="<?php echo BASE_URL ?>assets/client/assets/vendor/swiper/swiper-bundle.min.css">
	<link rel="stylesheet" href="<?php echo BASE_URL ?>assets/client/assets/vendor/simplebar/simplebar.min.css">
	<link rel="stylesheet" href="<?php echo BASE_URL ?>assets/client/assets/vendor/glightbox/glightbox.min.css">
	<link rel="preload" href="<?php echo BASE_URL ?>assets/client/assets/css/theme.min.css" as="style">
	<link rel="preload" href="<?php echo BASE_URL ?>assets/client/assets/css/theme.rtl.min.css" as="style">
	<link rel="stylesheet" href="<?php echo BASE_URL ?>assets/client/assets/css/theme.min.css" id="theme-styles">
</head>


<!-- Body -->

<body>

	<!-- Navigation bar (Page header) -->
	<header class="navbar navbar-expand-lg navbar-sticky bg-body d-block z-fixed p-0"
		data-sticky-navbar="{&quot;offset&quot;: 500}">
		<div class="container py-2 py-lg-3">
			<div class="d-flex align-items-center gap-3">

				<!-- Mobile offcanvas menu toggler (Hamburger) -->
				<button type="button" class="navbar-toggler me-4 me-md-2" data-bs-toggle="offcanvas"
					data-bs-target="#navbarNav" aria-controls="navbarNav" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<!-- Country slect visible on screens > 768px wide (md breakpoint) -->
				<div class="dropdown d-none d-md-block nav">
					<a class="nav-link dropdown-toggle py-1 px-0" href="#" data-bs-toggle="dropdown"
						aria-haspopup="true" aria-expanded="false" aria-label="Country select: USA">
						<div class="ratio ratio-1x1" style="width: 20px">
							<img src="<?php echo BASE_URL ?>/assets/client/assets/img/flags/en-us.png" alt="USA">
						</div>
					</a>
					<ul class="dropdown-menu fs-sm" style="--cz-dropdown-spacer: .5rem">
						<li>
							<a class="dropdown-item" href="#!">
								<img src="<?php echo BASE_URL ?>/assets/client/assets/img/flags/en-uk.png"
									class="flex-shrink-0 me-2" width="20" alt="United Kingdom">
								United Kingdom
							</a>
						</li>
						<li>
							<a class="dropdown-item" href="#!">
								<img src="<?php echo BASE_URL ?>/assets/client/assets/img/flags/fr.png"
									class="flex-shrink-0 me-2" width="20" alt="France">
								France
							</a>
						</li>
						<li>
							<a class="dropdown-item" href="#!">
								<img src="<?php echo BASE_URL ?>/assets/client/assets/img/flags/de.png"
									class="flex-shrink-0 me-2" width="20" alt="Deutschland">
								Deutschland
							</a>
						</li>
						<li>
							<a class="dropdown-item" href="#!">
								<img src="<?php echo BASE_URL ?>/assets/client/assets/img/flags/it.png"
									class="flex-shrink-0 me-2" width="20" alt="Italia">
								Italia
							</a>
						</li>
					</ul>
				</div>

				<div class="dropdown d-none d-md-block nav">
					<a class="nav-link animate-underline dropdown-toggle fw-normal py-1 px-0" href="#"
						data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<span class="animate-target">Washington</span>
					</a>
					<ul class="dropdown-menu fs-sm" style="--cz-dropdown-spacer: .5rem">
						<li><a class="dropdown-item" href="#!">Chicago</a></li>
						<li><a class="dropdown-item" href="#!">Los Angeles</a></li>
						<li><a class="dropdown-item" href="#!">New York</a></li>
						<li><a class="dropdown-item" href="#!">Philadelphia</a></li>
					</ul>
				</div>
			</div>

			<!-- Navbar brand (Logo) -->
			<a class="navbar-brand fs-2 py-0 m-0 me-auto me-sm-n5" href="<?php echo BASE_URL ?>">Five Clothes</a>
			<form class="d-none d-md-flex ms-3" action="<?php echo BASE_URL ?>" method="get" style="max-width:400px; width:100%;">
    <input type="hidden" name="action" value="search">
    <div class="input-group">
        <input type="text"
               class="form-control"
               name="keyword"
               placeholder="Tìm kiếm sản phẩm..."
               aria-label="Tìm kiếm sản phẩm"
               value="<?php echo htmlspecialchars($_GET['keyword'] ?? ''); ?>">
        <button class="btn btn-outline-secondary" type="submit">
            <i class="ci-search"></i>
        </button>
    </div>
</form>

			<!-- Button group -->
			<div class="d-flex align-items-center">

				<!-- Navbar stuck nav toggler -->
				<button type="button" class="navbar-toggler d-none navbar-stuck-show me-3" data-bs-toggle="collapse"
					data-bs-target="#stuckNav" aria-controls="stuckNav" aria-expanded="false"
					aria-label="Toggle navigation in navbar stuck state">
					<span class="navbar-toggler-icon"></span>
				</button>

				<!-- Theme switcher (light/dark/auto) -->
				<div class="dropdown">
					<button type="button"
						class="theme-switcher btn btn-icon btn-lg btn-outline-secondary fs-lg border-0 rounded-circle animate-scale"
						data-bs-toggle="dropdown" aria-expanded="false" aria-label="Toggle theme (light)">
						<span class="theme-icon-active d-flex animate-target">
							<i class="ci-sun"></i>
						</span>
					</button>
					<ul class="dropdown-menu" style="--cz-dropdown-min-width: 9rem">
						<li>
							<button type="button" class="dropdown-item active" data-bs-theme-value="light"
								aria-pressed="true">
								<span class="theme-icon d-flex fs-base me-2">
									<i class="ci-sun"></i>
								</span>
								<span class="theme-label">Light</span>
								<i class="item-active-indicator ci-check ms-auto"></i>
							</button>
						</li>
						<li>
							<button type="button" class="dropdown-item" data-bs-theme-value="dark" aria-pressed="false">
								<span class="theme-icon d-flex fs-base me-2">
									<i class="ci-moon"></i>
								</span>
								<span class="theme-label">Dark</span>
								<i class="item-active-indicator ci-check ms-auto"></i>
							</button>
						</li>
						<li>
							<button type="button" class="dropdown-item" data-bs-theme-value="auto" aria-pressed="false">
								<span class="theme-icon d-flex fs-base me-2">
									<i class="ci-auto"></i>
								</span>
								<span class="theme-label">Auto</span>
								<i class="item-active-indicator ci-check ms-auto"></i>
							</button>
						</li>
					</ul>
				</div>

				<!-- Search toggle button visible on screens < 992px wide (lg breakpoint) -->
				<button type="button"
					class="btn btn-icon btn-lg fs-xl btn-outline-secondary border-0 rounded-circle animate-shake d-lg-none"
					data-bs-toggle="offcanvas" data-bs-target="#searchBox" aria-controls="searchBox"
					aria-label="Toggle search bar">
					<i class="ci-search animate-target"></i>
				</button>

				<!-- Account button visible on screens > 768px wide (md breakpoint) -->
<div class="dropdown d-none d-md-inline-flex">
    <!-- Nút icon để bật dropdown -->
    <button
        class="btn btn-icon btn-lg fs-lg btn-outline-secondary border-0 rounded-circle"
        type="button"
        id="accountDropdown"
        data-bs-toggle="dropdown"
        aria-expanded="false"
    >
        <i class="ci-user animate-target"></i>
        <span class="visually-hidden">Account</span>
    </button>

    <!-- Menu dropdown -->
<ul class="dropdown-menu dropdown-menu-end mt-2" aria-labelledby="accountDropdown" style="min-width: 10rem;">
    <?php if (isset($_SESSION['user'])): ?>
        <!-- Nếu đã đăng nhập, hiển thị Profile + Logout -->
        <li>
            <a class="dropdown-item" href="<?= BASE_URL ?>?action=profile">
                <i class="ci-id-card me-2"></i> Xem Profile
            </a>
        </li>
        <!-- chỗ này thêm admin -->
        <?php if (is_admin()): ?>
        <li>
            <a class="dropdown-item" href="<?= BASE_URL ?>?action=admin_dashboard">
                <i class="ci-shield-lock me-2"></i> Quản trị
            </a>
        </li>
        <li><hr class="dropdown-divider"></li>
        <?php endif; ?>
        <li>
            <a class="dropdown-item text-danger" href="<?= BASE_URL ?>?action=logout">
                <i class="ci-sign-out me-2"></i> Logout
            </a>
        </li>
    <?php else: ?>
        <!-- Nếu chưa đăng nhập, hiển thị Login + Register -->
        <li>
            <a class="dropdown-item" href="<?= BASE_URL ?>?action=login_form">
                <i class="ci-sign-in me-2"></i> Login
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="<?= BASE_URL ?>?action=register_form">
                <i class="ci-user-plus me-2"></i> Register
            </a>
        </li>
    <?php endif; ?>
</ul>

</div>


				<!-- Wishlist button visible on screens > 768px wide (md breakpoint) -->
				<a class="btn btn-icon btn-lg fs-lg btn-outline-secondary border-0 rounded-circle animate-pulse d-none d-md-inline-flex"
					href="#!">
					<i class="ci-heart animate-target"></i>
					<span class="visually-hidden">Wishlist</span>
				</a>

				<!-- Cart button -->
<a href="<?= BASE_URL ?>?action=view_cart"
   class="btn btn-icon btn-lg fs-xl btn-outline-secondary position-relative border-0 rounded-circle animate-scale"
   aria-label="Giỏ hàng">
    <span
        class="position-absolute top-0 start-100 badge fs-xs text-bg-primary rounded-pill mt-1 ms-n4 z-2"
        style="--cz-badge-padding-y: .25em; --cz-badge-padding-x: .42em">
        <?= $cartItemCount ?? '0' /* hoặc hiện số lượng từ $_SESSION */ ?>
    </span>
    <i class="ci-shopping-bag animate-target me-1"></i>
</a>	

			</div>
		</div>

		<!-- Main navigation that turns into offcanvas on screens < 992px wide (lg breakpoint) -->
		<div class="collapse navbar-stuck-hide" id="stuckNav">
			<nav class="offcanvas offcanvas-start" id="navbarNav" tabindex="-1" aria-labelledby="navbarNavLabel">
				<div class="offcanvas-header py-3">
					<h5 class="offcanvas-title" id="navbarNavLabel">Browse Five Clothes</h5>
					<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
				</div>

				<!-- Country and City slects visible on screens < 768px wide (md breakpoint) -->
				<div class="offcanvas-header gap-3 d-md-none pt-0 pb-3">
					<div class="dropdown nav">
						<a class="nav-link dropdown-toggle py-1 px-0" href="#" data-bs-toggle="dropdown"
							aria-haspopup="true" aria-expanded="false" aria-label="Country select: USA">
							<div class="ratio ratio-1x1" style="width: 20px">
								<img src="<?php echo BASE_URL ?>/assets/client/assets/img/flags/en-us.png" alt="USA">
							</div>
						</a>
						<ul class="dropdown-menu fs-sm" style="--cz-dropdown-spacer: .5rem">
							<li>
								<a class="dropdown-item" href="#!">
									<img src="<?php echo BASE_URL ?>/assets/client/assets/img/flags/en-uk.png"
										class="flex-shrink-0 me-2" width="20" alt="United Kingdom">
									United Kingdom
								</a>
							</li>
							<li>
								<a class="dropdown-item" href="#!">
									<img src="<?php echo BASE_URL ?>/assets/client/assets/img/flags/fr.png"
										class="flex-shrink-0 me-2" width="20" alt="France">
									France
								</a>
							</li>
							<li>
								<a class="dropdown-item" href="#!">
									<img src="<?php echo BASE_URL ?>/assets/client/assets/img/flags/de.png"
										class="flex-shrink-0 me-2" width="20" alt="Deutschland">
									Deutschland
								</a>
							</li>
							<li>
								<a class="dropdown-item" href="#!">
									<img src="<?php echo BASE_URL ?>/assets/client/assets/img/flags/it.png"
										class="flex-shrink-0 me-2" width="20" alt="Italia">
									Italia
								</a>
							</li>
						</ul>
					</div>
					<div class="dropdown nav">
						<a class="nav-link animate-underline dropdown-toggle fw-normal py-1 px-0" href="#"
							data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<span class="animate-target">Washington</span>
						</a>
						<ul class="dropdown-menu fs-sm" style="--cz-dropdown-spacer: .5rem">
							<li><a class="dropdown-item" href="#!">Chicago</a></li>
							<li><a class="dropdown-item" href="#!">Los Angeles</a></li>
							<li><a class="dropdown-item" href="#!">New York</a></li>
							<li><a class="dropdown-item" href="#!">Philadelphia</a></li>
						</ul>
					</div>
				</div>
				<div class="offcanvas-body pt-1 pb-3 py-lg-0">
					<div class="container pb-lg-2 px-0 px-lg-3">

						<div class="position-relative d-lg-flex align-items-center justify-content-center">
							<ul class="navbar-nav position-relative me-xl-n5">
								<li class="nav-item pb-lg-2 me-lg-n2 me-xl-0">
									<a class="nav-link" href="<?php echo BASE_URL ?>">Trang chủ</a>
								</li>
								<li class="nav-item pb-lg-2 me-lg-n2 me-xl-0">
									<a class="nav-link" href="<?php echo BASE_URL ?>">Sản phẩm</a>
								</li>
								<li class="nav-item dropdown pb-lg-2 me-lg-n1 me-xl-0">
									<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
										data-bs-trigger="hover" data-bs-auto-close="outside" aria-expanded="false">Danh
										mục</a>
									<ul class="dropdown-menu" style="--cz-dropdown-spacer: .75rem">
										<?php
                                        foreach (get_categories() as $category) {?>
											<li>
												<a class="dropdown-item"
													href="<?php echo BASE_URL . '?mod=client&action=category&id=' . $category['id'] ?>">
													<?php echo $category['name'] ?>
												</a>
											</li>
										<?php }?>
									</ul>
								</li>
							</ul>
						</div>
					</div>
				</div>

				<!-- Account and Wishlist buttons visible on screens < 768px wide (md breakpoint) -->
				<div class="offcanvas-header border-top px-0 py-3 mt-3 d-md-none">
					<div class="nav nav-justified w-100">
						<a class="nav-link border-end" href="account-signin.html">
							<i class="ci-user fs-lg opacity-60 me-2"></i>
							Account
						</a>
						<a class="nav-link" href="#!">
							<i class="ci-heart fs-lg opacity-60 me-2"></i>
							Wishlist
						</a>
					</div>
				</div>
			</nav>
		</div>
	</header>


	<!-- Page content -->
	<main class="content-wrapper">