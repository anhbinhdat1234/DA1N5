<?php require_once __DIR__ . '/layout/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <!-- Thanh điều hướng bên trái với các liên kết quản trị -->
        <aside class="col-md-2 bg-dark text-white p-0">
            <div class="d-flex flex-column min-vh-100">
                <div class="p-3 border-bottom">
                    <!-- Tiêu đề cho sidebar -->
                    <h4 class="text-center">Quản trị viên</h4>
                </div>
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
        <!-- Nội dung chính của dashboard -->
        <main class="col-md-10 bg-light p-4">
            <!-- Tiêu đề chính của trang -->
            <h1 class="mb-4 text-primary text-center">
                <?= $title ?? 'Quản trị Hệ thống' ?>
            </h1>

            <!-- Thẻ tóm tắt -->
            <!-- Hiển thị các số liệu tổng quan như doanh thu, đơn hàng, v.v. -->
            <div class="row mb-4">
                <!-- Tổng doanh thu -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Tổng Doanh thu (30 ngày)</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= isset($revenueStats['total_revenue']) ? '$' . number_format($revenueStats['total_revenue'], 2) : '$0.00' ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tổng đơn hàng -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Tổng Đơn hàng (30 ngày)</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= $revenueStats['total_orders'] ?? 0 ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Giá trị đơn hàng trung bình -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Giá trị Đơn hàng Trung bình</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= isset($revenueStats['average_order_value']) ? '$' . number_format($revenueStats['average_order_value'], 2) : '$0.00' ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Người dùng mới -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Người dùng Mới (30 ngày)</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?= count($newUsers ?? []) ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (isset($view)) : ?>
                <!-- Load view cụ thể nếu có (ví dụ: product-index, users-index) -->
                <?php require_once PATH_VIEW_ADMIN . $view . '.php'; ?>
            <?php else: ?>
                <!-- Nội dung dashboard mặc định khi không có view cụ thể -->
                <!-- Charts Row -->
                <div class="row">
                    <!-- Biểu đồ doanh thu -->
                    <div class="col-xl-8 col-lg-7">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Tổng quan Doanh thu</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-area">
                                    <canvas id="revenueChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sản phẩm bán chạy -->
                    <div class="col-xl-4 col-lg-5">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Sản phẩm Bán chạy</h6>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($bestSellers)): ?>
                                    <?php foreach ($bestSellers as $product): ?>
                                        <div class="mb-3">
                                            <div class="d-flex align-items-center">
                                                <?php if (!empty($product['image_url'])): ?>
                                                    <img src="<?= htmlspecialchars($product['image_url']) ?>"
                                                        class="img-fluid rounded mr-3" width="50" alt="<?= htmlspecialchars($product['name']) ?>">
                                                <?php endif; ?>
                                                <div>
                                                    <div class="font-weight-bold"><?= htmlspecialchars($product['name']) ?></div>
                                                    <div class="small text-gray-600">
                                                        Đã bán: <?= $product['total_sold'] ?> |
                                                        Doanh thu: $<?= number_format($product['total_revenue'], 2) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-center text-muted">Chưa có sản phẩm bán chạy</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Second Row - Doanh thu theo danh mục và Sử dụng coupon -->
                <div class="row">
                    <!-- Doanh thu theo danh mục -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Doanh thu theo Danh mục</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Danh mục</th>
                                                <th>Đơn hàng</th>
                                                <th>Doanh thu</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($categorySales)): ?>
                                                <?php foreach ($categorySales as $category): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($category['name']) ?></td>
                                                        <td><?= $category['order_count'] ?></td>
                                                        <td>$<?= number_format($category['total_revenue'], 2) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="3" class="text-center">Chưa có dữ liệu</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sử dụng coupon -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Sử dụng Coupon</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Mã Coupon</th>
                                                <th>Số lần sử dụng</th>
                                                <th>Tổng Giảm giá</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($couponUsage)): ?>
                                                <?php foreach ($couponUsage as $coupon): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($coupon['coupon_code']) ?></td>
                                                        <td><?= $coupon['usage_count'] ?></td>
                                                        <td>$<?= number_format($coupon['total_discount'], 2) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="3" class="text-center">Chưa có coupon được sử dụng</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Third Row - Đơn hàng gần đây và Người dùng mới -->
                <div class="row">
                    <!-- Đơn hàng gần đây -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Đơn hàng Gần đây</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Mã Đơn</th>
                                                <th>Khách hàng</th>
                                                <th>Tổng tiền</th>
                                                <th>Trạng thái</th>
                                                <th>Ngày</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($recentOrders)): ?>
                                                <?php foreach ($recentOrders as $order): ?>
                                                    <tr>
                                                        <td><?= $order['id'] ?></td>
                                                        <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                                                        <td>$<?php echo $order['total'] ? number_format($order['total'], 2) : '$0.00'; ?></td>
                                                        <span class="badge badge-<?php
                                                                                    echo $order['status'] === 'completed' ? 'success' : ($order['status'] === 'cancelled' ? 'danger' : 'warning');
                                                                                    ?>">
                                                            <?php echo ucfirst($order['status']); ?>
                                                        </span>
                                                        </td>
                                                        <td><?php echo date('d M, Y', strtotime($order['created_at'])); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="5" class="text-center">Chưa có đơn hàng</td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Người dùng mới -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Người dùng Mới</h6>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($newUsers)): ?>
                                    <?php foreach ($newUsers as $user): ?>
                                        <div class="mb-3 d-flex align-items-center">
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px;">
                                                <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                            </div>
                                            <div class="ml-3">
                                                <div class="font-weight-bold"><?= htmlspecialchars($user['name']) ?></div>
                                                <div class="small text-gray-600">
                                                    <?= htmlspecialchars($user['email']) ?> |
                                                    Tham gia: <?= date('d M, Y', strtotime($user['created_at'])) ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-center text-muted">Chưa có người dùng mới</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fourth Row - Trạng thái đơn hàng và Cảnh báo tồn kho -->
                <div class="row">
                    <!-- Trạng thái đơn hàng -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Trạng thái Đơn hàng</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart-pie pt-4 pb-2">
                                    <canvas id="orderStatusChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cảnh báo tồn kho thấp -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Cảnh báo Tồn kho Thấp</h6>
                            </div>
                            <div class="card-body">
                                <?php if (empty($lowStockProducts)): ?>
                                    <div class="text-center text-success">Không có sản phẩm tồn kho thấp</div>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Sản phẩm</th>
                                                    <th>Biến thể</th>
                                                    <th>Tồn kho</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($lowStockProducts as $product): ?>
                                                    <tr>
                                                        <td>
                                                            <?php if (!empty($product['image_url'])): ?>
                                                                <img src="<?= htmlspecialchars($product['image_url']) ?>"
                                                                    class="img-fluid rounded mr-2" width="30" alt="<?= htmlspecialchars($product['name']) ?>">
                                                            <?php endif; ?>
                                                            <?= htmlspecialchars($product['name']) ?>
                                                        </td>
                                                        <td>
                                                            <?= htmlspecialchars($product['color'] ?? 'Không có') ?>,
                                                            <?= htmlspecialchars($product['size'] ?? 'Không có') ?>
                                                        </td>
                                                        <td class="<?= $product['stock'] <= 0 ? 'text-danger' : 'text-warning' ?>">
                                                            <?= $product['stock'] ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<!-- JavaScript cho Biểu đồ -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Biểu đồ Doanh thu
    const revenueData = <?php echo json_encode($revenueStats['daily'] ?? []); ?>;
    const revenueLabels = revenueData.map(item => new Date(item.date).toLocaleDateString('vi-VN', {
        month: 'short',
        day: 'numeric'
    }));
    const revenueValues = revenueData.map(item => item.daily_revenue);

    const revenueCtx = document.getElementById('revenueChart');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Doanh thu Hàng ngày',
                data: revenueValues,
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: 'rgba(78, 115, 223, 1)'
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Biểu đồ Trạng thái Đơn hàng
    const orderStatusData = <?php echo json_encode($orderStatusCounts ?? []); ?>;
    const orderStatusLabels = orderStatusData.map(item => {
        const status = item.status.toLowerCase();
        if (status === 'completed') return 'Hoàn thành';
        if (status === 'cancelled') return 'Đã hủy';
        if (status === 'pending') return 'Chờ xử lý';
        if (status === 'processing') return 'Đang xử lý';
        return item.status.charAt(0).toUpperCase() + item.status.slice(1);
    });
    const orderStatusValues = orderStatusData.map(item => item.count);

    const orderStatusCtx = document.getElementById('orderStatusChart');
    new Chart(orderStatusCtx, {
        type: 'doughnut',
        data: {
            labels: orderStatusLabels,
            datasets: [{
                data: orderStatusValues,
                backgroundColor: [
                    'rgba(78, 115, 223, 0.8)',
                    'rgba(28, 200, 138, 0.8)',
                    'rgba(231, 74, 59, 0.8)',
                    'rgba(246, 194, 62, 0.8)'
                ],
                hoverBackgroundColor: [
                    'rgba(78, 115, 223, 1)',
                    'rgba(28, 200, 138, 1)',
                    'rgba(231, 74, 59, 1)',
                    'rgba(246, 194, 62, 1)'
                ],
                hoverBorderColor: 'rgba(234, 236, 244, 1)'
            }]
        },
        options: {
            maintainAspectRatio: false,
            cutout: '70%'
        }
    });
</script>