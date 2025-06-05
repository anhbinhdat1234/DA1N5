<nav class="container pt-2 pt-xxl-3 my-3 my-md-4" aria-label="breadcrumb">
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Trang chủ</a></li>
		<li class="breadcrumb-item active" aria-current="page"><?= $category['name'] ?></li>
	</ol>
</nav>
<div class="container">
	<div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 gy-4 gy-md-5 pb-xxl-3">
		<?php foreach ($products as $product): ?>
			<div class="col mb-2 mb-sm-3 mb-md-0">
				<div class="animate-underline hover-effect-opacity">
					<div class="position-relative mb-3">
						<span
							class="badge text-bg-danger position-absolute top-0 start-0 z-2 mt-2 mt-sm-3 ms-2 ms-sm-3">NEW</span>
						<button type="button"
							class="btn btn-icon btn-secondary animate-pulse fs-base bg-transparent border-0 position-absolute top-0 end-0 z-2 mt-1 mt-sm-2 me-1 me-sm-2"
							aria-label="Add to Wishlist">
							<i class="ci-heart animate-target"></i>
						</button>
						<a class="d-flex bg-body-tertiary rounded p-3"
							href="<?= BASE_URL . '?mode=client&action=product_ct&id=' . $product['id'] ?>">
							<div class="ratio" style="--cz-aspect-ratio: calc(308 / 274 * 100%)">
								<img src="<?= BASE_URL . $product['image_url'] ?>" alt="Image">
							</div>
						</a>
						<?php if (!empty($product['sizes'])): ?>
							<div
								class="hover-effect-target position-absolute start-0 bottom-0 w-100 z-2 opacity-0 pb-2 pb-sm-3 px-2 px-sm-3">
								<div
									class="d-flex align-items-center justify-content-center gap-2 gap-xl-3 bg-body rounded-2 p-2">
									<?php foreach ($product['sizes'] as $size): ?>
										<span span class="fs-xs fw-medium text-secondary-emphasis py-1 px-sm-2"><?= $size ?></span>
									<?php endforeach; ?>
								</div>
							</div>
						<?php endif; ?>
					</div>
					<div class="nav mb-2">
						<a class="nav-link animate-target min-w-0 text-dark-emphasis p-0"
							href="<?= BASE_URL . '?mode=client&action=product_ct&id=' . $product['id'] ?>">
							<span class="text-truncate">
								<?= $product['name'] ?>
							</span>
						</a>
					</div>
					<div class="h6 mb-2"><?= number_format($product['price']) ?></div>
					<div class="position-relative">
						<div class="hover-effect-target fs-xs text-body-secondary opacity-100">
							<?= count($product['colors']) ?> màu
						</div>
						<?php if (!empty($product['colors'])): ?>
							<div class="hover-effect-target d-flex gap-2 position-absolute top-0 start-0 opacity-0">
								<?php foreach ($product['colors'] as $key => $color): ?>
									<input type="radio" class="btn-check" name="colors-<?= $product['id'] ?>"
										id="color-<?= $product['id'] . '-' . $key ?>" checked="">
									<label for="color-<?= $product['id'] . '-' . $key ?>" class="btn btn-color fs-base"
										style="color: <?= get_color_code($color) ?>">
										<span class="visually-hidden"><?= $color ?></span>
									</label>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>