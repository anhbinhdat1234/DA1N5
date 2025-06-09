<?php
// views/client/product-detail.php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= htmlspecialchars($product['name']) ?> – Chi tiết sản phẩm</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/client/assets/css/theme.min.css">
</head>
<body>
  <div class="container py-5">
    <div class="row">
      <!-- Ảnh sản phẩm -->
      <div class="col-md-6 mb-4">
        <a href="<?= BASE_URL . $product['image_url'] ?>" data-glightbox>
          <img src="<?= BASE_URL . $product['image_url'] ?>"
               class="img-fluid rounded"
               alt="<?= htmlspecialchars($product['name']) ?>">
        </a>
      </div>
      <!-- Thông tin & lựa chọn -->
      <div class="col-md-6">
        <h1 class="h3"><?= htmlspecialchars($product['name']) ?></h1>
        <p class="h4 text-danger mb-4"><?= number_format($product['price'],0,',','.') ?>₫</p>

        <form action="<?= BASE_URL ?>?action=add_to_cart" method="post" class="mb-4">
          <input type="hidden" name="variant_id" id="variant_id" value="">
          <input type="hidden" name="quantity"   id="quantity"   value="1">

          <!-- Màu -->
          <?php if (!empty($product['colors'])): ?>
            <div class="mb-3">
              <label class="form-label">Màu sắc:</label>
              <div class="d-flex gap-2">
                <?php foreach ($product['colors'] as $k => $c):
                  $id = "color-$k"; ?>
                  <input type="radio" class="btn-check" name="color" id="<?= $id ?>"
                         value="<?= htmlspecialchars($c) ?>" <?= $k===0?'checked':'' ?>>
                  <label for="<?= $id ?>"
                         class="btn btn-outline-secondary"
                         style="background: <?= get_color_code($c) ?>; width:32px; height:32px;"></label>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>

          <!-- Size -->
          <?php if (!empty($product['sizes'])): ?>
            <div class="mb-3">
              <label class="form-label">Size:</label>
              <div class="d-flex gap-2">
                <?php foreach ($product['sizes'] as $k => $s):
                  $id = "size-$k"; ?>
                  <input type="radio" class="btn-check" name="size" id="<?= $id ?>"
                         value="<?= htmlspecialchars($s) ?>" <?= $k===0?'checked':'' ?>>
                  <label for="<?= $id ?>" class="btn btn-outline-secondary px-3 py-2">
                    <?= htmlspecialchars($s) ?>
                  </label>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>

          <!-- Số lượng -->
          <div class="input-group mb-3" style="max-width:160px;">
            <button class="btn btn-outline-secondary" type="button" id="btn-decrement"><i class="ci-minus"></i></button>
            <input type="text" id="qty-input" class="form-control text-center" value="1" readonly>
            <button class="btn btn-outline-secondary" type="button" id="btn-increment"><i class="ci-plus"></i></button>
          </div>

          <!-- Thêm vào giỏ -->
          <button type="submit" id="btnAddToCart" class="btn btn-dark w-100 mb-4" disabled>
            Thêm vào giỏ
          </button>

          <!-- Tồn kho -->
          <div>
            <div class="d-flex justify-content-between mb-1">
              <span><strong>Tồn kho:</strong></span>
              <span id="stock-count">0</span>
            </div>
            <div class="progress" style="height:6px;">
              <div id="stock-bar" class="progress-bar bg-danger" style="width:0%"></div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs mt-5" role="tablist">
      <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-desc">Mô tả</button>
      </li>
      <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-wash">Hướng dẫn giặt</button>
      </li>
      <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-delivery">Giao & Đổi trả</button>
      </li>
      <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-reviews">
          Đánh giá (<?= count($reviews) ?>)
        </button>
      </li>
    </ul>
    <div class="tab-content pt-4">
      <!-- Mô tả -->
      <div class="tab-pane fade show active" id="tab-desc">
        <?= $product['description'] ?>
      </div>
      <!-- Hướng dẫn giặt -->
      <div class="tab-pane fade fs-sm" id="tab-wash">
        <div class="row row-cols-1 row-cols-md-2 g-4">
          <div class="col">
            <dl class="mb-0">
              <dt>Giặt máy với nước lạnh</dt>
              <dd>Đảo mặt trong sản phẩm trước khi giặt để bảo vệ màu sắc và vải.</dd>
              <dt>Chế độ giặt nhẹ</dt>
              <dd>Chọn chế độ giặt nhẹ hoặc đồ mỏng để tránh ma sát mạnh.</dd>
            </dl>
          </div>
          <div class="col">
            <dl class="mb-0">
              <dt>Không dùng thuốc tẩy mạnh</dt>
              <dd>Dùng chất tẩy nhẹ, tránh làm hỏng vải.</dd>
              <dt>Phơi khô nhẹ</dt>
              <dd>Phơi thẳng hoặc sấy ở nhiệt độ thấp, tránh co rút.</dd>
            </dl>
          </div>
        </div>
      </div>
      <!-- Giao hàng & Đổi trả -->
      <div class="tab-pane fade fs-sm" id="tab-delivery">
        <div class="row row-cols-1 row-cols-md-2 g-4">
          <div class="col">
            <h6>Giao hàng</h6>
            <ul class="list-unstyled mb-0">
              <li>Tiêu chuẩn: 3–7 ngày làm việc</li>
              <li>Nhanh: 1–3 ngày làm việc</li>
            </ul>
            <p class="mt-2 text-muted">Phí tùy khu vực.</p>
          </div>
          <div class="col">
            <h6>Đổi trả</h6>
            <p>Chấp nhận 30 ngày, sản phẩm chưa qua sử dụng.</p>
          </div>
        </div>
      </div>
      <!-- Đánh giá -->
      <div class="tab-pane fade" id="tab-reviews">
        <?php if ($reviews): ?>
          <?php foreach ($reviews as $rev): ?>
            <div class="border-bottom py-4">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <strong><?= htmlspecialchars($rev['user_name']) ?></strong>
                <span class="text-muted fs-sm"><?= date('d/m/Y H:i', strtotime($rev['created_at'])) ?></span>
              </div>
              <div class="d-flex gap-1 mb-2">
                <?php for($i=1;$i<=5;$i++): ?>
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
                <?php for($i=5;$i>=1;$i--): ?>
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
            Bạn cần <a href="<?= BASE_URL ?>?action=login_form">đăng nhập</a> để đánh giá.
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script>
    const variants = <?= json_encode($product['variants']) ?>;
    document.addEventListener('DOMContentLoaded', () => {
      const maxStock     = Math.max(...variants.map(v=>v.stock),1);
      const stockCountEl = document.getElementById('stock-count');
      const stockBarEl   = document.getElementById('stock-bar');
      const btnAdd       = document.getElementById('btnAddToCart');
      const varInput     = document.getElementById('variant_id');
      const qtyInput     = document.getElementById('qty-input');
      const hiddenQty    = document.getElementById('quantity');

      function getSelVariant(){
        const color = document.querySelector('input[name="color"]:checked')?.value;
        const size  = document.querySelector('input[name="size"]:checked')?.value;
        return variants.find(v=>v.color===color&&v.size===size) || null;
      }

      function updateStock(){
        const v = getSelVariant();
        if(!v){
          stockCountEl.textContent='0';
          stockBarEl.style.width='0%';
          btnAdd.disabled=true;
          varInput.value='';
          return;
        }
        stockCountEl.textContent=v.stock;
        stockBarEl.style.width=(v.stock/maxStock*100)+'%';
        varInput.value=v.id;
        btnAdd.disabled = v.stock<=0;
        if(+qtyInput.value>v.stock){
          qtyInput.value=v.stock;
          hiddenQty.value=v.stock;
        }
      }

      document.querySelectorAll('input[name="color"],input[name="size"]')
        .forEach(el=>el.addEventListener('change',updateStock));

      document.getElementById('btn-increment').addEventListener('click',()=>{
        const v=getSelVariant(); if(!v) return;
        let q=+qtyInput.value; if(q<v.stock) q++;
        qtyInput.value=q; hiddenQty.value=q;
      });
      document.getElementById('btn-decrement').addEventListener('click',()=>{
        let q=+qtyInput.value; if(q>1) q--;
        qtyInput.value=q; hiddenQty.value=q;
      });

      updateStock();
    });
  </script>
</body>
</html>
