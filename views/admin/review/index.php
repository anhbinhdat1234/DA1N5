
<?php require_once __DIR__ . '/../layout/header.php'; ?>
<?php require_once __DIR__ . '/../layout/sidebar.php'; ?><?php
$userId = $_SESSION['user']['id'] ?? 0;
$role   = $_SESSION['user']['role'] ?? 'user';
?>

<div class="card shadow-sm mb-4">
  <div class="card-header bg-primary text-white fw-bold">
    <i class="bi bi-chat-dots"></i> Danh sách bình luận
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle mb-0">
        <thead class="table-light text-center">
          <tr>
            <th style="width:40px;">#</th>
            <th>Sản phẩm</th>
            <th>Người bình luận</th>
            <th>Nội dung</th>
            <th style="width:70px;">Đánh giá</th>
            <th style="width:90px;">Trạng thái</th>
            <th style="width:140px;">Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($reviews)): ?>
            <tr>
              <td colspan="7" class="text-center text-muted">Chưa có bình luận nào.</td>
            </tr>
          <?php else: ?>
            <?php foreach ($reviews as $i => $review): ?>
              <tr>
                <td class="text-center"><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($review['product_name']) ?></td>
                <td><?= htmlspecialchars($review['user_name']) ?></td>
                <td>
                  <?php if (($review['is_hidden'] ?? 0)): ?>
                    <em class="text-danger">(Đã ẩn)</em>
                  <?php else: ?>
                    <?= nl2br(htmlspecialchars($review['content'])) ?>
                  <?php endif; ?>
                </td>
                <td class="text-center"><?= $review['rating'] ?>⭐</td>
                <td class="text-center">
                  <?php if (($review['is_hidden'] ?? 0)): ?>
                    <span class="badge bg-danger">Ẩn</span>
                  <?php else: ?>
                    <span class="badge bg-success">Hiện</span>
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <?php
                  $isOwnerOrAdmin = ($userId == ($review['user_id'] ?? 0)) || ($role == 'admin');
                  if ($isOwnerOrAdmin):
                  ?>
                    <a href="<?= BASE_URL_ADMIN ?>&action=review-toggle-hidden&id=<?= $review['id'] ?>"
                      class="btn btn-sm <?= ($review['is_hidden'] ?? 0) ? 'btn-success' : 'btn-warning' ?>"
                      title="<?= ($review['is_hidden'] ?? 0) ? 'Hiện bình luận' : 'Ẩn bình luận' ?>">
                      <i class="bi <?= ($review['is_hidden'] ?? 0) ? 'bi-eye' : 'bi-eye-slash' ?>"></i>
                      <?= ($review['is_hidden'] ?? 0) ? 'Hiện' : 'Ẩn' ?>
                    </a>
                    <a href="<?= BASE_URL_ADMIN ?>&action=review-delete&id=<?= $review['id'] ?>"
                      class="btn btn-sm btn-outline-danger ms-1"
                      onclick="return confirm('Bạn chắc chắn muốn xóa bình luận này?');"
                      title="Xóa bình luận">
                      <i class="bi bi-trash"></i> Xóa
                    </a>
                  <?php else: ?>
                    <span class="text-muted fst-italic">Không thể thao tác</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>