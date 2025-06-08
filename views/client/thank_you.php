<div class="container py-5 text-center">
  <h2 class="mb-4">Cảm ơn bạn đã đặt hàng!</h2>
  <p>Mã đơn hàng của bạn là <strong>#<?= htmlspecialchars($order['id']) ?></strong>.</p>
  <p>Chúng tôi sẽ liên hệ lại sớm nhất.</p>
  <a href="<?= BASE_URL ?>" class="btn btn-primary mt-3">Quay về trang chủ</a>
</div>
