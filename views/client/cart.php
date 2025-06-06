<div class="container py-5">
    <h2 class="mb-4">Giỏ hàng của bạn</h2>

    <?php if (empty($cartItems)): ?>
        <div class="alert alert-info">Giỏ hàng đang trống.</div>
        <a href="<?= BASE_URL ?>" class="btn btn-primary">Quay về trang chủ</a>
    <?php else: ?>
        <!-- Chú ý ở đây: form action chỉ là "?action=update_cart" để router hiểu đúng -->
        <form action="?action=update_cart" method="post">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th scope="col" style="width:100px;">Ảnh</th>
                        <th scope="col">Sản phẩm</th>
                        <th scope="col">Giá</th>
                        <th scope="col">Số lượng</th>
                        <th scope="col">Thành tiền</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td>
                            <img 
                                src="<?= BASE_URL . '/' . htmlspecialchars($item['thumbnail']) ?>" 
                                class="img-fluid" 
                                alt="<?= htmlspecialchars($item['product_name']) ?>" 
                                style="object-fit:cover; width:100px; height:100px;"
                            >
                        </td>
                        <td>
                            <a href="<?= BASE_URL ?>?action=product_ct&id=<?= $item['product_id'] ?>">
                                <?= htmlspecialchars($item['product_name']) ?>
                            </a><br>
                            <small>Màu: <?= htmlspecialchars($item['color']) ?>, Size: <?= htmlspecialchars($item['size']) ?></small>
                        </td>
                        <td>
                            <?= number_format($item['price'], 0, ',', '.') ?>₫
                        </td>
                        <td>
                            <input 
                                type="number" 
                                name="quantities[<?= $item['cart_id'] ?>]" 
                                value="<?= (int)$item['quantity'] ?>" 
                                min="1" 
                                class="form-control" 
                                style="width:80px;"
                            >
                        </td>
                        <td>
                            <?= number_format($item['subtotal'], 0, ',', '.') ?>₫
                        </td>
                        <td>
                            <button 
                                type="submit" 
                                name="remove" 
                                value="<?= $item['cart_id'] ?>" 
                                class="btn btn-sm btn-outline-danger"
                                title="Xóa sản phẩm"
                            >
                                Xóa
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <button type="submit" class="btn btn-primary me-2">Cập nhật giỏ hàng</button>
                    <a href="?action=clear_cart" class="btn btn-outline-danger">
                        Xóa toàn bộ
                    </a>
                </div>
                <div class="text-end">
                    <h5>Tổng cộng: 
                        <span class="text-danger">
                            <?= number_format($total, 0, ',', '.') ?>₫
                        </span>
                    </h5>
                    <a href="?action=checkout_form" class="btn btn-success mt-2">
                        Tiến hành thanh toán
                    </a>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>
