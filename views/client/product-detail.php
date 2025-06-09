<?php
// views/client/product-detail.php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= htmlspecialchars($product['name']) ?></title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/client/assets/css/theme.min.css">
</head>
<body>
  <div class="container py-5">
    <div class="row">
      <div class="col-md-6 mb-4">
        <img src="<?= BASE_URL . $product['image_url'] ?>" class="img-fluid rounded" alt="">
      </div>
      <div class="col-md-6">
        <h1 class="h3"><?= htmlspecialchars($product['name']) ?></h1>
        <p class="h4 text-danger"><?= number_format($product['price'],0,',','.') ?>₫</p>

        <form action="<?= BASE_URL ?>?action=add_to_cart" method="post">
          <input type="hidden" name="variant_id" id="variant_id">
          <input type="hidden" name="quantity"   id="quantity"   value="1">

          <!-- Color -->
          <?php if (!empty($product['colors'])): ?>
            <div class="mb-3">
              <label class="form-label">Màu:</label>
              <div class="d-flex gap-2">
                <?php foreach ($product['colors'] as $k => $c): ?>
                  <?php $id="color-$k"; ?>
                  <input type="radio" name="color" id="<?= $id ?>" value="<?= htmlspecialchars($c) ?>" <?= $k===0?'checked':'' ?> class="btn-check">
                  <label for="<?= $id ?>" class="btn btn-outline-secondary" style="background:<?= get_color_code($c) ?>;"> </label>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>

          <!-- Size -->
          <?php if (!empty($product['sizes'])): ?>
            <div class="mb-3">
              <label class="form-label">Size:</label>
              <div class="d-flex gap-2">
                <?php foreach ($product['sizes'] as $k => $s): ?>
                  <?php $id="size-$k"; ?>
                  <input type="radio" name="size" id="<?= $id ?>" value="<?= htmlspecialchars($s) ?>" <?= $k===0?'checked':'' ?> class="btn-check">
                  <label for="<?= $id ?>" class="btn btn-outline-secondary"><?= htmlspecialchars($s) ?></label>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>

          <!-- Quantity -->
          <div class="input-group mb-3" style="max-width: 160px;">
            <button class="btn btn-outline-secondary" type="button" id="btn-decrement"><i class="ci-minus"></i></button>
            <input type="text" id="qty-input" class="form-control text-center" value="1" readonly>
            <button class="btn btn-outline-secondary" type="button" id="btn-increment"><i class="ci-plus"></i></button>
          </div>

          <!-- Add to Cart & Stock -->
          <button type="submit" id="btnAddToCart" class="btn btn-dark mb-3 w-100" disabled>Thêm vào giỏ</button>
          <div class="mb-3">
            <div class="d-flex justify-content-between">
              <span>Tồn kho:</span>
              <span id="stock-count">0</span>
            </div>
            <div class="progress" style="height:6px;">
              <div id="stock-bar" class="progress-bar bg-danger" style="width:0%"></div>
            </div>
          </div>
        </form>
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
