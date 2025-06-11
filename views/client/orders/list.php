<div class="container my-5">
    <h2 class="mb-4">Danh sách đơn hàng</h2>
    
    <?php if (empty($orders)): ?>
        <div class="alert alert-info">Bạn chưa có đơn hàng nào</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Ngày đặt</th>
                        <th>Số sản phẩm</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?= $order['id'] ?></td>
                        <td><?= date('d/m/Y', strtotime($order['created_at'])) ?></td>
                        <td><?= $order['item_count'] ?></td>
                        <td><?= number_format($order['total_amount']) ?>₫</td>
                        <td>
                            <span class="badge 
                                <?= $order['status'] == 'completed' ? 'bg-success' : 
                                   ($order['status'] == 'cancelled' ? 'bg-danger' : 'bg-warning') ?>">
                                <?= $this->getStatusText($order['status']) ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?= BASE_URL ?>?action=order_detail&id=<?= $order['id'] ?>" 
                               class="btn btn-sm btn-primary">
                                Xem chi tiết
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>