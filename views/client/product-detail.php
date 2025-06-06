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
    <title><?= htmlspecialchars($product['name']) ?> - Chi tiết sản phẩm</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/client/assets/css/theme.min.css">
</head>
<body>

<!-- Breadcrumb -->
<nav class="container pt-2 pt-xxl-3 my-3 my-md-4" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>?action=product">Sản phẩm</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($product['name']) ?></li>
    </ol>
</nav>

<!-- Product gallery and details -->
<section class="container">
    <div class="row">
        <!-- Gallery -->
        <div class="col-md-6 pb-4 pb-md-0 mb-2 mb-sm-3 mb-md-0">
            <div class="position-relative">
                <span class="badge text-bg-danger position-absolute top-0 start-0 z-2 mt-3 mt-sm-4 ms-3 ms-sm-4">
                    NEW
                </span>
                <button type="button"
                        class="btn btn-icon btn-secondary animate-pulse fs-lg bg-transparent border-0 position-absolute top-0 end-0 z-2 mt-2 mt-sm-3 me-2 me-sm-3"
                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-sm"
                        data-bs-title="Add to Wishlist" aria-label="Add to Wishlist">
                    <i class="ci-heart animate-target"></i>
                </button>
                <a class="hover-effect-scale hover-effect-opacity position-relative d-flex rounded overflow-hidden mb-3 mb-sm-4 mb-md-3 mb-lg-4"
                   href="<?= BASE_URL . $product['image_url'] ?>" data-glightbox="" data-gallery="product-gallery">
                    <i class="ci-zoom-in hover-effect-target fs-3 text-white position-absolute top-50 start-50 translate-middle opacity-0 z-2"></i>
                    <div class="ratio hover-effect-target bg-body-tertiary rounded"
                         style="--cz-aspect-ratio: calc(706 / 636 * 100%)">
                        <img src="<?= BASE_URL . $product['image_url'] ?>"
                             alt="<?= htmlspecialchars($product['name']) ?>">
                    </div>
                </a>
            </div>
        </div>

        <!-- Product details and options -->
        <div class="col-md-6">
            <div class="ps-md-4 ps-xl-5">

                <!-- Reviews -->
                <a class="d-none d-md-flex align-items-center gap-2 text-decoration-none mb-3" href="#reviews">
                    <div class="d-flex gap-1 fs-sm">
                        <i class="ci-star-filled text-warning"></i>
                        <i class="ci-star-filled text-warning"></i>
                        <i class="ci-star-filled text-warning"></i>
                        <i class="ci-star-filled text-warning"></i>
                        <i class="ci-star text-body-tertiary opacity-75"></i>
                    </div>
                    <span class="text-body-tertiary fs-sm">23 Đánh giá</span>
                </a>

                <!-- Title -->
                <h1 class="h3"><?= htmlspecialchars($product['name']) ?></h1>

                <!-- Description (rút gọn) -->
                <p class="fs-sm mb-0">
                    <?= implode(' ', array_slice(explode(' ', strip_tags($product['description'])), 0, 100)) . '...' ?>
                </p>

                <!-- Price -->
                <div class="h4 d-flex align-items-center my-4">
                    <?= number_format($product['price'], 0, ',', '.') ?>₫
                </div>

                <!-- Add to Cart Form -->
                <form action="<?= BASE_URL ?>?action=add_to_cart" method="post">
                    <input type="hidden" name="variant_id" id="variant_id" value="">
                    <input type="hidden" name="quantity" id="quantity" value="1">

                    <!-- Color options -->
                    <?php if (!empty($product['colors'])): ?>
                        <div class="mb-4">
                            <label class="form-label fw-semibold pb-1 mb-2">Màu sắc:</label>
                            <div class="d-flex flex-wrap gap-2" id="color-options">
                                <?php foreach ($product['colors'] as $key => $color):
                                    $inputId = 'color-' . $key; ?>
                                    <input type="radio"
                                           class="btn-check"
                                           name="color"
                                           id="<?= $inputId ?>"
                                           value="<?= htmlspecialchars($color) ?>"
                                           <?= $key === 0 ? 'checked' : '' ?>>
                                    <label for="<?= $inputId ?>"
                                           class="btn btn-image p-0"
                                           data-color="<?= htmlspecialchars($color) ?>">
                                        <div>
                                            <div class="rounded-circle border-2"
                                                 style="background-color: <?= get_color_code($color) ?>; width: 30px; height: 30px;">
                                            </div>
                                        </div>
                                        <span class="visually-hidden"><?= htmlspecialchars($color) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Size options -->
                    <?php if (!empty($product['sizes'])): ?>
                        <div class="mb-4">
                            <label class="form-label fw-semibold pb-1 mb-2">Size:</label>
                            <div class="d-flex flex-wrap gap-2" id="size-options">
                                <?php foreach ($product['sizes'] as $key => $size):
                                    $inputId = 'size-' . $key; ?>
                                    <input type="radio"
                                           class="btn-check"
                                           name="size"
                                           id="<?= $inputId ?>"
                                           value="<?= htmlspecialchars($size) ?>"
                                           <?= $key === 0 ? 'checked' : '' ?>>
                                    <label for="<?= $inputId ?>" class="btn btn-outline-secondary px-3">
                                        <?= htmlspecialchars($size) ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Quantity & Add to Cart Button -->
                    <div class="d-flex gap-3 pb-3 pb-lg-4 mb-3 align-items-center">
                        <div class="count-input flex-shrink-0 d-flex align-items-center">
                            <button type="button" id="btn-decrement" class="btn btn-icon btn-lg" aria-label="Decrement quantity">
                                <i class="ci-minus"></i>
                            </button>
                            <input type="number"
                                   class="form-control form-control-lg text-center"
                                   min="1"
                                   value="1"
                                   readonly
                                   id="qty-input">
                            <button type="button" id="btn-increment" class="btn btn-icon btn-lg" aria-label="Increment quantity">
                                <i class="ci-plus"></i>
                            </button>
                        </div>
                        <button type="submit" class="btn btn-lg btn-dark w-100" id="btnAddToCart" disabled>
                            Thêm vào giỏ
                        </button>
                    </div>
                </form>

                <!-- Info list -->
                <ul class="list-unstyled gap-3 pb-3 pb-lg-4 mb-3">
                    <li class="d-flex flex-wrap fs-sm">
                        <span class="d-flex align-items-center fw-medium text-dark-emphasis me-2">
                            <i class="ci-clock fs-base me-2"></i>
                            Giao hàng dự kiến:
                        </span>
                        15 - 27 Tháng 3, 2024
                    </li>
                    <li class="d-flex flex-wrap fs-sm">
                        <span class="d-flex align-items-center fw-medium text-dark-emphasis me-2">
                            <i class="ci-delivery fs-base me-2"></i>
                            Miễn phí vận chuyển & đổi trả:
                        </span>
                        Cho đơn hàng trên 2.400.000₫
                    </li>
                </ul>

                <!-- Stock status -->
                <div class="d-flex flex-wrap justify-content-between fs-sm mb-2">
                    <span class="fw-medium text-dark-emphasis me-2">🔥 Nhanh tay! Sản phẩm sắp hết hàng</span>
                    <span>Còn <span id="stock-count" class="fw-medium text-danger">?</span> sản phẩm</span>
                </div>
                <div class="progress" role="progressbar" aria-label="Còn lại trong kho" style="height: 4px">
                    <div id="stock-bar" class="progress-bar rounded-pill bg-danger" style="width: 0%"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Product details tabs -->
<section class="container pt-5 mt-2 mt-sm-3 mt-lg-4 mt-xl-5">
    <!-- Nav tabs -->
    <ul class="nav nav-underline flex-nowrap border-bottom" role="tablist">
        <li class="nav-item me-md-1" role="presentation">
            <button type="button" class="nav-link active" id="description-tab" data-bs-toggle="tab"
                    data-bs-target="#description-tab-pane" role="tab" aria-controls="description-tab-pane"
                    aria-selected="true">
                Mô tả
            </button>
        </li>
        <li class="nav-item me-md-1" role="presentation">
            <button type="button" class="nav-link" id="washing-tab" data-bs-toggle="tab"
                    data-bs-target="#washing-tab-pane" role="tab" aria-controls="washing-tab-pane" aria-selected="false">
                Hướng dẫn<span class="d-none d-md-inline">&nbsp;giặt</span>
            </button>
        </li>
        <li class="nav-item me-md-1" role="presentation">
            <button type="button" class="nav-link" id="delivery-tab" data-bs-toggle="tab"
                    data-bs-target="#delivery-tab-pane" role="tab" aria-controls="delivery-tab-pane" aria-selected="false">
                Giao hàng<span class="d-none d-md-inline">&nbsp;& đổi trả</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button type="button" class="nav-link" id="reviews-tab" data-bs-toggle="tab"
                    data-bs-target="#reviews-tab-pane" role="tab" aria-controls="reviews-tab-pane" aria-selected="false">
                Đánh giá<span class="d-none d-md-inline">&nbsp;(23)</span>
            </button>
        </li>
    </ul>

    <div class="tab-content pt-4 mt-sm-1 mt-md-3">
        <!-- Description tab -->
        <div class="tab-pane fade show active" id="description-tab-pane" role="tabpanel"
             aria-labelledby="description-tab">
            <div class="row">
                <div class="col-lg-6 fs-sm">
                    <?= $product['description'] ?>
                </div>
                <div class="col-lg-6 col-xl-5 offset-xl-1">
                    <div class="row row-cols-2 g-4 my-0 my-lg-n2">
                        <div class="col">
                            <div class="py-md-1 py-lg-2 pe-sm-2">
                                <i class="ci-feather fs-3 text-body-emphasis mb-2 mb-md-3"></i>
                                <h6 class="fs-sm mb-2">Nhẹ nhàng</h6>
                                <p class="fs-sm mb-0">Thiết kế với vải châu Âu nhẹ nhàng, hoàn hảo cho cuộc sống năng động.</p>
                            </div>
                        </div>
                        <div class="col">
                            <div class="py-md-1 py-lg-2 ps-sm-2">
                                <i class="ci-hanger fs-3 text-body-emphasis mb-2 mb-md-3"></i>
                                <h6 class="fs-sm mb-2">Vừa vặn hoàn hảo</h6>
                                <p class="fs-sm mb-0">Trang phục của chúng tôi phù hợp với mọi dáng người, giúp bạn tìm được phong cách lý tưởng!</p>
                            </div>
                        </div>
                        <div class="col">
                            <div class="py-md-1 py-lg-2 pe-sm-2">
                                <i class="ci-delivery-2 fs-3 text-body-emphasis mb-2 mb-md-3"></i>
                                <h6 class="fs-sm mb-2">Giao hàng miễn phí</h6>
                                <p class="fs-sm mb-0">Miễn phí giao hàng tận nơi cho tất cả đơn hàng trên 250$.</p>
                            </div>
                        </div>
                        <div class="col">
                            <div class="py-md-1 py-lg-2 ps-sm-2">
                                <i class="ci-leaf fs-3 text-body-emphasis mb-2 mb-md-3"></i>
                                <h6 class="fs-sm mb-2">Bền vững</h6>
                                <p class="fs-sm mb-0">Chúng tôi tự hào cung cấp chế độ bảo hành trọn đời cho tất cả sản phẩm.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab hướng dẫn giặt -->
        <div class="tab-pane fade fs-sm" id="washing-tab-pane" role="tabpanel" aria-labelledby="washing-tab">
            <p>Thực hiện theo các hướng dẫn giặt dưới đây sẽ giúp kéo dài tuổi thọ cho váy denim của bạn và giữ cho nó luôn đẹp lâu hơn.</p>
            <div class="row row-cols-1 row-cols-md-2">
                <div class="col mb-3 mb-md-0">
                    <dl class="pe-lg-2 pe-xl-3 mb-0">
                        <dt>Giặt máy với nước lạnh</dt>
                        <dd>Đảo mặt trong váy denim trước khi cho vào máy giặt. Sử dụng nước lạnh giúp bảo vệ màu sắc và kết cấu vải.</dd>
                        <dt>Chế độ giặt nhẹ</dt>
                        <dd>Chọn chế độ giặt nhẹ hoặc dành cho đồ mỏng trên máy giặt để tránh làm hư hại vải do ma sát quá mạnh.</dd>
                        <dt>Chất tẩy nhẹ</dt>
                        <dd>Sử dụng chất tẩy nhẹ, chuyên dùng cho denim hoặc vải mỏng. Tránh dùng chất tẩy mạnh hoặc thuốc tẩy vì có thể làm hỏng sợi vải.</dd>
                        <dt>Không quá tải máy giặt</dt>
                        <dd class="mb-0">Không nhét quá nhiều đồ vào máy giặt cùng lúc để đảm bảo giặt sạch và tránh ma sát gây hư váy denim.</dd>
                    </dl>
                </div>
                <div class="col">
                    <dl class="ps-lg-2 ps-xl-3 mb-0">
                        <dt>Giặt riêng</dt>
                        <dd>Giặt riêng váy denim với các món đồ có khóa kéo, cúc hoặc phụ kiện sắc nhọn có thể làm rách vải.</dd>
                        <dt>Không dùng nước xả vải</dt>
                        <dd>Tránh dùng nước xả vải vì nó có thể để lại cặn trên denim và làm giảm độ cứng tự nhiên đặc trưng của vải denim.</dd>
                        <dt>Phơi khô hoặc sấy nhẹ</dt>
                        <dd>Sau khi giặt, tạo lại form váy và phơi khô bằng cách để phẳng hoặc sấy ở nhiệt độ thấp. Tránh nhiệt độ cao để không làm co rút hoặc biến dạng váy.</dd>
                        <dt>Ủi</dt>
                        <dd class="mb-0">Nếu cần, ủi mặt trong váy với nhiệt độ thấp đến vừa phải. Tránh ủi trực tiếp lên các chi tiết trang trí hoặc túi để không làm hỏng váy.</dd>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Tab giao hàng và đổi trả -->
        <div class="tab-pane fade fs-sm" id="delivery-tab-pane" role="tabpanel" aria-labelledby="delivery-tab">
            <div class="row row-cols-1 row-cols-md-2">
                <div class="col mb-3 mb-md-0">
                    <div class="pe-lg-2 pe-xl-3">
                        <h6>Giao hàng</h6>
                        <p>Chúng tôi luôn cố gắng giao váy denim midi có túi đến bạn nhanh nhất có thể. Thời gian giao hàng dự kiến như sau:</p>
                        <ul class="list-unstyled">
                            <li>Giao hàng tiêu chuẩn: <span class="text-dark-emphasis fw-semibold">Trong vòng 3-7 ngày làm việc</span></li>
                            <li>Giao hàng nhanh: <span class="text-dark-emphasis fw-semibold">Trong vòng 1-3 ngày làm việc</span></li>
                        </ul>
                        <p>Lưu ý thời gian giao hàng có thể thay đổi tùy theo vị trí của bạn cũng như các chương trình khuyến mãi hoặc ngày lễ hiện hành. Bạn có thể theo dõi đơn hàng bằng mã vận chuyển được cung cấp sau khi đơn hàng được gửi đi.</p>
                    </div>
                </div>
                <div class="col">
                    <div class="ps-lg-2 ps-xl-3">
                        <h6>Đổi trả</h6>
                        <p>Chúng tôi mong bạn hoàn toàn hài lòng với váy denim midi có túi của mình. Nếu vì bất kỳ lý do nào bạn không hài lòng với sản phẩm, bạn có thể trả lại trong vòng 30 ngày kể từ ngày nhận hàng để được hoàn tiền hoặc đổi sản phẩm.</p>
                        <p>Để đủ điều kiện đổi trả, sản phẩm phải chưa qua sử dụng, chưa giặt, còn nguyên trạng thái ban đầu với tem mác còn nguyên. Vui lòng đảm bảo tất cả bao bì vẫn còn nguyên vẹn khi gửi trả lại.</p>
                        <p class="mb-0">Để bắt đầu thủ tục đổi trả, vui lòng liên hệ với bộ phận chăm sóc khách hàng cùng số đơn hàng và lý do trả hàng. Chúng tôi sẽ cung cấp nhãn gửi trả và hướng dẫn chi tiết. Lưu ý phí vận chuyển trả lại sẽ không được hoàn lại.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab đánh giá khách hàng -->
        <div class="tab-pane fade" id="reviews-tab-pane" role="tabpanel" aria-labelledby="reviews-tab">
            <!-- Giữ nguyên phần đánh giá tĩnh như mẫu -->
            <div class="border-bottom py-4">
                <div class="row py-sm-2">
                    <div class="col-md-4 col-lg-3 mb-3 mb-md-0">
                        <div class="d-flex h6 mb-2">
                            Rafael Marquez
                            <i class="ci-check-circle text-success mt-1 ms-2" data-bs-toggle="tooltip"
                               data-bs-custom-class="tooltip-sm" title="Khách hàng đã xác thực"></i>
                        </div>
                        <div class="fs-sm mb-2 mb-md-3">25 tháng 6, 2024</div>
                        <div class="d-flex gap-1 fs-sm">
                            <i class="ci-star-filled text-warning"></i>
                            <i class="ci-star-filled text-warning"></i>
                            <i class="ci-star-filled text-warning"></i>
                            <i class="ci-star-filled text-warning"></i>
                            <i class="ci-star text-body-tertiary opacity-75"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-9">
                        <p class="mb-md-4">Tôi hoàn toàn yêu thích chiếc ghế này! Đúng chuẩn mình cần để hoàn thiện phòng khách phong cách Scandinavian. Chân gỗ làm tăng sự ấm áp, thiết kế thì cực kỳ timeless. Ghế vừa thoải mái vừa chắc chắn, không thể hài lòng hơn với lựa chọn này!</p>
                        <div class="d-sm-flex justify-content-between">
                            <div class="d-flex align-items-center fs-sm fw-medium text-dark-emphasis pb-2 pb-sm-0 mb-1 mb-sm-0">
                                <i class="ci-check fs-base me-1" style="margin-top: .125rem"></i>
                                Có, tôi giới thiệu sản phẩm này
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <div class="fs-sm fw-medium text-dark-emphasis me-1">Có hữu ích không?</div>
                                <button type="button" class="btn btn-sm btn-secondary">
                                    <i class="ci-thumbs-up fs-sm ms-n1 me-1"></i> 0
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Bạn có thể thêm các đánh giá khác tương tự -->
        </div>
    </div>
</section>

<script>
    // Truyền danh sách variants từ PHP sang JavaScript
    const productVariants = <?= json_encode($product['variants']) ?>;
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const colorRadios = document.querySelectorAll('input[name="color"]');
    const sizeRadios = document.querySelectorAll('input[name="size"]');
    const stockCount = document.getElementById('stock-count');
    const stockBar = document.getElementById('stock-bar');
    const variantInput = document.getElementById('variant_id');
    const btnAdd = document.getElementById('btnAddToCart');
    const qtyInput = document.getElementById('qty-input');
    const quantityField = document.getElementById('quantity');
    const btnDecrement = document.getElementById('btn-decrement');
    const btnIncrement = document.getElementById('btn-increment');

    function updateVariantAndStock() {
        const selectedColor = document.querySelector('input[name="color"]:checked')?.value;
        const selectedSize  = document.querySelector('input[name="size"]:checked')?.value;

        if (!selectedColor || !selectedSize) {
            variantInput.value = '';
            stockCount.textContent = '?';
            stockBar.style.width = '0%';
            btnAdd.disabled = true;
            return;
        }

        const variant = productVariants.find(v =>
            v.color === selectedColor && v.size === selectedSize
        );

        if (variant) {
            variantInput.value = variant.id;
            const stock = variant.stock;
            const maxStock = 100; // Giả sử max = 100, điều chỉnh tùy thực tế
            const percent = Math.min((stock / maxStock) * 100, 100);

            stockCount.textContent = stock > 0 ? stock : 'Hết hàng';
            stockBar.style.width = percent + '%';
            stockBar.classList.toggle('bg-danger', stock <= 5);
            stockBar.classList.toggle('bg-warning', stock > 5 && stock <= 10);
            stockBar.classList.toggle('bg-success', stock > 10);

            btnAdd.disabled = stock <= 0;
        } else {
            variantInput.value = '';
            stockCount.textContent = 'Không khả dụng';
            stockBar.style.width = '0%';
            stockBar.className = 'progress-bar rounded-pill bg-secondary';
            btnAdd.disabled = true;
        }
    }

    function updateQuantityField() {
        let current = parseInt(qtyInput.value) || 1;
        if (current < 1) {
            current = 1;
        }
        qtyInput.value = current;
        quantityField.value = current;
    }

    // Gắn sự kiện cho nút “–”
    btnDecrement.addEventListener('click', function (e) {
        e.preventDefault();
        let value = parseInt(qtyInput.value) || 1;
        if (value > 1) {
            value -= 1;
            qtyInput.value = value;
            updateQuantityField();
        }
    });

    // Gắn sự kiện cho nút “+”
    btnIncrement.addEventListener('click', function (e) {
        e.preventDefault();
        let value = parseInt(qtyInput.value) || 1;
        value += 1;
        qtyInput.value = value;
        updateQuantityField();
    });

    // Khi đổi màu hoặc size
    colorRadios.forEach(radio => radio.addEventListener('change', updateVariantAndStock));
    sizeRadios.forEach(radio => radio.addEventListener('change', updateVariantAndStock));

    // Khởi tạo lần đầu
    updateVariantAndStock();
    updateQuantityField();
});
</script>

</body>
</html>
