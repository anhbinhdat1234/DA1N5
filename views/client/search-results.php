<?php
/**
 * Biến đầu vào:
 *   - $keyword: chuỗi tìm kiếm
 *   - $products: mảng kết quả
 */
?>
<div class="container py-5">
    <h2 class="mb-4">Kết quả tìm kiếm: <em><?php echo htmlspecialchars($keyword); ?></em></h2>

    <?php if (empty($products)): ?>
        <div class="alert alert-warning">
            Không tìm thấy sản phẩm nào phù hợp với từ khóa "<strong><?php echo htmlspecialchars($keyword); ?></strong>".
        </div>
    <?php else: ?>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 gx-3 gy-4">
<?php foreach ($products as $product): ?>
    <div class="col">
        <div class="card h-100 shadow-sm">
            <a href="<?php echo BASE_URL . '?action=product_ct&id=' . $product['id']; ?>">
                <img 
                    src="<?php echo BASE_URL . '/' . htmlspecialchars($product['thumbnail'] ?? 'path/to/default-thumb.jpg'); ?>" 
                    class="card-img-top" 
                    alt="<?php echo htmlspecialchars($product['name']); ?>" 
                    style="object-fit:cover; height:200px;"
                >
            </a>
            <div class="card-body d-flex flex-column">
                <h6 class="card-title mb-2">
                    <a class="text-dark text-decoration-none"
                       href="<?php echo BASE_URL . '?action=product_ct&id=' . $product['id']; ?>">
                        <?php echo htmlspecialchars($product['name']); ?>
                    </a>
                </h6>
                <div class="mt-auto">
                    <span class="text-primary fw-bold">
                        <?php echo number_format($product['price'], 0, ',', '.'); ?>₫
                    </span>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

        </div>
    <?php endif; ?>
</div>
