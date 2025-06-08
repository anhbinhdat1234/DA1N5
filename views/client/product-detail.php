<?php
// views/client/product-detail.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> – Chi tiết sản phẩm</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/client/assets/css/theme.min.css">
</head>
<body>

<!-- Breadcrumb -->
<nav class="container pt-3 my-4" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>?action=product">Sản phẩm</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($product['name']) ?></li>
    </ol>
</nav>

<!-- Product gallery & info -->
<section class="container mb-5">
    <div class="row">
        <!-- Gallery -->
        <div class="col-md-6 mb-4">
            <div class="position-relative">
                <?php if (!empty($product['is_new'])): ?>
                    <span class="badge bg-danger position-absolute top-0 start-0 m-3">NEW</span>
                <?php endif; ?>
                <a href="<?= BASE_URL . $product['image_url'] ?>" data-glightbox data-gallery="product-gallery">
                    <div class="ratio ratio-1x1 bg-light rounded overflow-hidden">
                        <img src="<?= BASE_URL . $product['image_url'] ?>"
                             alt="<?= htmlspecialchars($product['name']) ?>"
                             class="w-100 h-100 object-fit-cover">
                    </div>
                </a>
            </div>
        </div>

        <!-- Details & options -->
        <div class="col-md-6">
            <!-- Title & rating -->
            <h1 class="h3"><?= htmlspecialchars($product['name']) ?></h1>
            <div class="mb-3 d-flex align-items-center">
                <div class="me-2">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="ci-star<?= $i <= ($product['average_rating'] ?? 5) ? '-filled text-warning' : '' ?>"></i>
                    <?php endfor; ?>
                </div>
                <span class="text-muted"><?= count($reviews) ?> đánh giá</span>
            </div>

            <!-- Short description -->
            <p class="text-muted mb-4">
                <?= implode(' ', array_slice(explode(' ', strip_tags($product['description'])), 0, 20)) . '...' ?>
            </p>

            <!-- Price -->
            <div class="h4 text-dark mb-4"><?= number_format($product['price'], 0, ',', '.') ?>₫</div>

            <!-- Add to cart form -->
            <form action="<?= BASE_URL ?>?action=add_to_cart" method="post">
                <input type="hidden" name="variant_id" id="variant_id" value="">
                <input type="hidden" name="quantity"  id="quantity"   value="1">

                <!-- Color -->
                <?php if (!empty($product['colors'])): ?>
                    <div class="mb-3">
                        <label class="form-label">Màu sắc:</label>
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($product['colors'] as $k => $color):
                                $id = "color-$k"; ?>
                                <input type="radio" class="btn-check" name="color" id="<?= $id ?>"
                                       value="<?= htmlspecialchars($color) ?>" <?= $k === 0 ? 'checked' : '' ?>>
                                <label for="<?= $id ?>" class="btn btn-outline-secondary p-2"
                                       style="background-color: <?= get_color_code($color) ?>; width:32px; height:32px;"></label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Size -->
                <?php if (!empty($product['sizes'])): ?>
                    <div class="mb-3">
                        <label class="form-label">Size:</label>
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($product['sizes'] as $k => $size):
                                $id = "size-$k"; ?>
                                <input type="radio" class="btn-check" name="size" id="<?= $id ?>"
                                       value="<?= htmlspecialchars($size) ?>" <?= $k === 0 ? 'checked' : '' ?>>
                                <label for="<?= $id ?>" class="btn btn-outline-secondary px-3 py-2">
                                    <?= htmlspecialchars($size) ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

        <!-- Quantity & Add to Cart -->
        <div class="d-flex align-items-center gap-3 mb-4">
        <!-- input-group với kích thước large -->
        <div class="input-group input-group-lg" style="max-width: 160px;">
            <button
            class="btn btn-outline-secondary"
            type="button"
            id="btn-decrement"
            ><i class="ci-minus"></i></button>
            <input
            type="text"
            id="qty-input"
            class="form-control text-center"
            value="1"
            readonly
            >
            <button
            class="btn btn-outline-secondary"
            type="button"
            id="btn-increment"
            ><i class="ci-plus"></i></button>
        </div>

        <!-- Nút thêm vào giỏ cũng large, flex-grow để nó giãn hết -->
        <button
            type="submit"
            id="btnAddToCart"
            class="btn btn-dark btn-lg flex-grow-1"
            disabled
        >
            Thêm vào giỏ
        </button>
        </div>

            </form>

            <!-- Stock status -->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="fw-semibold">🔥 Nhanh tay! Sản phẩm sắp hết hàng</span>
                    <span>Còn <span id="stock-count" class="fw-bold text-danger">?</span> sản phẩm</span>
                </div>
                <div class="progress" style="height: 6px;">
                    <div id="stock-bar" class="progress-bar bg-danger rounded-pill" style="width: 0%;"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tabs: mô tả, hướng dẫn, giao hàng, đánh giá -->
<section class="container mb-5">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-desc">
                Mô tả
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-wash">
                Hướng dẫn giặt
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-delivery">
                Giao hàng & Đổi trả
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-reviews">
                Đánh giá (<?= count($reviews) ?>)
            </button>
        </li>
    </ul>
    <div class="tab-content pt-4">
        <div class="tab-pane fade show active" id="tab-desc">
            <?= $product['description'] ?>
        </div>
        <div class="tab-pane fade fs-sm" id="tab-wash">
            <!-- Nội dung hướng dẫn giặt -->
        </div>
        <div class="tab-pane fade fs-sm" id="tab-delivery">
            <!-- Nội dung giao hàng & đổi trả -->
        </div>
        <div class="tab-pane fade" id="tab-reviews">
            <?php if ($reviews): ?>
                <?php foreach ($reviews as $rev): ?>
                    <div class="border-bottom py-4">
                        <div class="d-flex mb-2">
                            <strong><?= htmlspecialchars($rev['user_name']) ?></strong>
                            <?php if ($rev['rating'] >= 4): ?>
                                <i class="ci-check-circle text-success ms-2" title="Xác thực"></i>
                            <?php endif; ?>
                        </div>
                        <div class="text-muted fs-sm mb-2">
                            <?= date('d/m/Y H:i', strtotime($rev['created_at'])) ?>
                        </div>
                        <div class="d-flex gap-1 mb-2">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="ci-star<?= $i <= $rev['rating'] ? '-filled text-warning' : '' ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <p><?= nl2br(htmlspecialchars($rev['content'])) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center py-4">Chưa có đánh giá nào.</p>
            <?php endif; ?>

            <?php if (isset($_SESSION['user'])): ?>
                <form method="post" class="mt-4">
                    <h5>Viết đánh giá của bạn</h5>
                    <div class="mb-3">
                        <label class="form-label">Số sao:</label>
                        <select name="rating" class="form-select w-auto">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <option value="<?= $i ?>"><?= $i ?> sao</option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nội dung:</label>
                        <textarea name="content" class="form-control" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                </form>
            <?php else: ?>
                <div class="alert alert-info mt-4">
                    Bạn cần <a href="<?= BASE_URL ?>?action=login">đăng nhập</a> để đánh giá.
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
    // Dữ liệu variants từ PHP
    const productVariants = <?= json_encode($product['variants']) ?>;
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const colorInputs = document.querySelectorAll('input[name="color"]');
    const sizeInputs  = document.querySelectorAll('input[name="size"]');
    const stockCount  = document.getElementById('stock-count');
    const stockBar    = document.getElementById('stock-bar');
    const variantId   = document.getElementById('variant_id');
    const btnAdd      = document.getElementById('btnAddToCart');
    const qtyInput    = document.getElementById('qty-input');
    const qtyField    = document.getElementById('quantity');
    const btnDec      = document.getElementById('btn-decrement');
    const btnInc      = document.getElementById('btn-increment');

    function updateVariantAndStock() {
        const color = document.querySelector('input[name="color"]:checked')?.value;
        const size  = document.querySelector('input[name="size"]:checked')?.value;
        if (!color || !size) {
            stockCount.textContent = '?';
            stockBar.style.width   = '0%';
            btnAdd.disabled        = true;
            return;
        }
        const variant = productVariants.find(v => v.color === color && v.size === size);
        if (!variant) {
            stockCount.textContent = '—';
            stockBar.style.width   = '0%';
            btnAdd.disabled        = true;
            return;
        }
        variantId.value = variant.id;
        const stock = variant.stock;
        const max   = 100; // điều chỉnh nếu cần
        const pct   = Math.min((stock / max) * 100, 100);
        stockCount.textContent = stock > 0 ? stock : 'Hết hàng';
        stockBar.style.width   = pct + '%';
        // đổi màu bar theo ngưỡng
        stockBar.classList.toggle('bg-danger', stock <= 5);
        stockBar.classList.toggle('bg-warning', stock > 5 && stock <= 10);
        stockBar.classList.toggle('bg-success', stock > 10);
        btnAdd.disabled = stock <= 0;
    }

    function updateQuantity() {
        let v = parseInt(qtyInput.value) || 1;
        if (v < 1) v = 1;
        qtyInput.value = v;
        qtyField.value = v;
    }

    btnDec.addEventListener('click', () => {
        updateQuantity(qtyInput.value = (parseInt(qtyInput.value) || 1) - 1);
    });
    btnInc.addEventListener('click', () => {
        updateQuantity(qtyInput.value = (parseInt(qtyInput.value) || 1) + 1);
    });

    colorInputs.forEach(i => i.addEventListener('change', updateVariantAndStock));
    sizeInputs.forEach(i => i.addEventListener('change', updateVariantAndStock));

    // Khởi tạo lần đầu
    updateVariantAndStock();
    updateQuantity();
});
</script>
</body>
</html>
