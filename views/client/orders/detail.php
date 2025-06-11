<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Chi tiết đơn hàng #<?= $order['id'] ?></h2>
        <a href="<?= BASE_URL ?>?action=orders" class="btn btn-outline-secondary">
            ← Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Sản phẩm</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Đơn giá</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($orderItems as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['product_name']) ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td><?= number_format($item['price']) ?> VNĐ</td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Trạng thái:</strong>
                        <span class="badge 
                            <?= $order['status'] == 'completed' ? 'bg-success' : 
                               ($order['status'] == 'cancelled' ? 'bg-danger' : 'bg-warning') ?>">
                            <?= $this->getStatusText($order['status']) ?>
                        </span>
                    </div>
                    <div class="mb-3">
                        <strong>Ngày đặt:</strong>
                        <?= date('H:i d/m/Y', strtotime($order['created_at'])) ?>
                    </div>
                    <div class="mb-3">
                        <strong>Tổng tiền:</strong>
                        <?= number_format($order['total']) ?>₫
                    </div>
                    <?php if ($order['discount_amount'] > 0): ?>
                    <div class="mb-3">
                        <strong>Giảm giá:</strong>
                        -<?= number_format($order['discount_amount']) ?>₫
                        <?php if ($order['coupon_code']): ?>
                            (Mã: <?= $order['coupon_code'] ?>)
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <strong>Thành tiền:</strong>
                        <?= number_format($order['total'] - $order['discount_amount']) ?>₫
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Thông tin giao hàng</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Địa chỉ:</strong>
                        <?= $order['address'] ?>
                    </div>
                    <div class="mb-3">
                        <strong>Điện thoại:</strong>
                        <?= $order['phone'] ?>
                    </div>
                    <?php if (!empty($order['note'])): ?>
                    <div class="mb-3">
                        <strong>Ghi chú:</strong>
                        <?= $order['note'] ?>
                    </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <strong>Trạng thái vận chuyển:</strong>
                        <span class="badge 
                            <?= $order['shipping_status'] == 'delivered' ? 'bg-success' : 
                               ($order['shipping_status'] == 'shipping' ? 'bg-primary' : 'bg-secondary') ?>">
                            <?= $this->getShippingStatusText($order['shipping_status']) ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
