<!-- Hero slider -->
<section class="bg-body-tertiary">
	<div class="container">
		<div class="row">
			<!-- Titles master slider -->
			<div class="col-md-6 col-lg-5 d-flex flex-column">
				<div class="py-4 mt-auto">
					<div class="swiper pb-1 pt-3 pt-sm-4 py-md-4 py-lg-3" data-swiper="{
				  &quot;spaceBetween&quot;: 24,
				  &quot;loop&quot;: true,
				  &quot;speed&quot;: 400,
				  &quot;controlSlider&quot;: &quot;#heroImages&quot;,
				  &quot;pagination&quot;: {
					&quot;el&quot;: &quot;#sliderBullets&quot;,
					&quot;clickable&quot;: true
				  },
				  &quot;autoplay&quot;: {
					&quot;delay&quot;: 5500,
					&quot;disableOnInteraction&quot;: false
				  }
				}">
						<div class="swiper-wrapper align-items-center">
							<!-- Item -->
							<?php foreach ($sliders as $slider): ?>
								<div class="swiper-slide text-center text-md-start">
									<p class="fs-xl mb-2 mb-lg-3 mb-xl-4"><?= $slider['title'] ?></p>
									<h2 class="display-4 text-uppercase mb-4 mb-xl-5"><?= $slider['subtitle'] ?></h2>
									<a class="btn btn-lg btn-outline-dark" href="<?= BASE_URL . $slider['link'] ?>">
										Mua sắm ngay
										<i class="ci-arrow-up-right fs-lg ms-2 me-n1"></i>
									</a>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>

				<!-- Slider bullets (pagination) -->
				<div
					class="d-flex justify-content-center justify-content-md-start pb-4 pb-xl-5 mt-n1 mt-md-auto mb-md-3 mb-lg-4">
					<div class="swiper-pagination position-static w-auto pb-1" id="sliderBullets"></div>
				</div>
			</div>

			<!-- Linked images (controlled slider) -->
			<div class="col-md-6 col-lg-7 align-self-end">
				<div class="position-relative ms-md-n4">
					<div class="ratio" style="--cz-aspect-ratio: calc(662 / 770 * 100%)"></div>
					<div class="swiper position-absolute top-0 start-0 w-100 h-100 user-select-none" id="heroImages"
						data-swiper="{
				  &quot;allowTouchMove&quot;: false,
				  &quot;loop&quot;: true,
				  &quot;effect&quot;: &quot;fade&quot;,
				  &quot;fadeEffect&quot;: {
					&quot;crossFade&quot;: true
				  }
				}">
						<div class="swiper-wrapper">
							<?php foreach ($sliders as $slider): ?>
								<div class="swiper-slide">
									<img src="<?= BASE_URL ?><?= $slider['image_url'] ?>" class="rtl-flip" alt="Image">
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Featured products -->
<section class="container pb-5 mt-3 mb-2 mb-sm-3 mb-lg-4 mb-xl-5">
	<h2 class="text-center pb-2 pb-sm-3">Sản phẩm mới nhất</h2>

	<!-- Nav pills -->
	<div class="row g-0 overflow-x-auto pb-2 pb-sm-3 mb-3">
		<div class="col-auto pb-1 pb-sm-0 mx-auto">
			<ul class="nav nav-pills flex-nowrap text-nowrap">
				<li class="nav-item">
					<a class="nav-link active" data-categoryv1="all" href="#">Tất cả</a>
				</li>
				<?php foreach (get_categories() as $category): ?>
					<li class="nav-item">
						<a class="nav-link" data-categoryv1="<?= $category['id'] ?>"
							href="<?= BASE_URL . '?mode=client&action=category&id=' . $category['id'] ?>">
							<?= $category['name'] ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>

	<!-- Products grid -->
	<div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 gy-4 gy-md-5 pb-xxl-3">
		<!-- Item -->
		<?php foreach ($newArrivals as $product): ?>
			<div data-category="<?= $product['category_id'] ?>" class="col mb-2 mb-sm-3 mb-md-0">
				<div class="animate-underl
				ine hover-effect-opacity">
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
										<span class="fs-xs fw-medium text-secondary-emphasis py-1 px-sm-2"><?= $size ?></span>
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
</section>


<!-- Instagram feed -->
<section class="container pt-5 mt-1 mt-sm-2 mt-md-3 mt-lg-4 mt-xl-5">
	<div class="text-center pt-xxl-3 pb-2 pb-md-3">
		<h2 class="pb-2 mb-1">
			<span class="animate-underline">
				<a class="animate-target text-dark-emphasis text-decoration-none" href="#!">#fiveclothes</a>
			</span>
		</h2>
		<p>Tìm thêm cảm hứng trên Instagram của chúng tôi</p>
	</div>
	<div class="overflow-x-auto pb-3 mb-n3" data-simplebar="">
		<div class="d-flex gap-2 gap-md-3 gap-lg-4" style="min-width: 700px">
			<a class="hover-effect-scale hover-effect-opacity position-relative w-100 overflow-hidden" href="#!">
				<span
					class="hover-effect-target position-absolute top-0 start-0 w-100 h-100 bg-black bg-opacity-25 opacity-0 z-1"></span>
				<i
					class="ci-instagram hover-effect-target fs-4 text-white position-absolute top-50 start-50 translate-middle opacity-0 z-2"></i>
				<div class="hover-effect-target ratio ratio-1x1">
					<img src="<?= BASE_URL ?>/assets/client/assets/img/instagram/01.jpg" alt="Instagram image">
				</div>
			</a>
			<a class="hover-effect-scale hover-effect-opacity position-relative w-100 overflow-hidden" href="#!">
				<span
					class="hover-effect-target position-absolute top-0 start-0 w-100 h-100 bg-black bg-opacity-25 opacity-0 z-1"></span>
				<i
					class="ci-instagram hover-effect-target fs-4 text-white position-absolute top-50 start-50 translate-middle opacity-0 z-2"></i>
				<div class="hover-effect-target ratio ratio-1x1">
					<img src="<?= BASE_URL ?>/assets/client/assets/img/instagram/02.jpg" alt="Instagram image">
				</div>
			</a>
			<a class="hover-effect-scale hover-effect-opacity position-relative w-100 overflow-hidden" href="#!">
				<span
					class="hover-effect-target position-absolute top-0 start-0 w-100 h-100 bg-black bg-opacity-25 opacity-0 z-1"></span>
				<i
					class="ci-instagram hover-effect-target fs-4 text-white position-absolute top-50 start-50 translate-middle opacity-0 z-2"></i>
				<div class="hover-effect-target ratio ratio-1x1">
					<img src="<?= BASE_URL ?>/assets/client/assets/img/instagram/03.jpg" alt="Instagram image">
				</div>
			</a>
			<a class="hover-effect-scale hover-effect-opacity position-relative w-100 overflow-hidden" href="#!">
				<span
					class="hover-effect-target position-absolute top-0 start-0 w-100 h-100 bg-black bg-opacity-25 opacity-0 z-1"></span>
				<i
					class="ci-instagram hover-effect-target fs-4 text-white position-absolute top-50 start-50 translate-middle opacity-0 z-2"></i>
				<div class="hover-effect-target ratio ratio-1x1">
					<img src="<?= BASE_URL ?>/assets/client/assets/img/instagram/04.jpg" alt="Instagram image">
				</div>
			</a>
			<a class="hover-effect-scale hover-effect-opacity position-relative w-100 overflow-hidden" href="#!">
				<span
					class="hover-effect-target position-absolute top-0 start-0 w-100 h-100 bg-black bg-opacity-25 opacity-0 z-1"></span>
				<i
					class="ci-instagram hover-effect-target fs-4 text-white position-absolute top-50 start-50 translate-middle opacity-0 z-2"></i>
				<div class="hover-effect-target ratio ratio-1x1">
					<img src="<?= BASE_URL ?>/assets/client/assets/img/instagram/05.jpg" alt="Instagram image">
				</div>
			</a>
		</div>
	</div>
</section>
<script>
	document.addEventListener("DOMContentLoaded", () => {
		const navContainer = document.querySelector(".nav-pills");
		const productItems = document.querySelectorAll("[data-category]");
		const filterProducts = (selectedCategory) => {
			console.log(selectedCategory);

			productItems.forEach((item) => {
				item.style.display =
					selectedCategory === "all" || item.dataset.category === selectedCategory
						? "block"
						: "none";
			});
		};

		navContainer.addEventListener("click", (e) => {
			const link = e.target.closest(".nav-link");
			if (!link) return;
			e.preventDefault();
			navContainer.querySelectorAll(".nav-link").forEach((l) => {
				l.classList.remove("active");
				l.setAttribute("aria-current", "false");
			});
			link.classList.add("active");
			link.setAttribute("aria-current", "true");

			filterProducts(link.dataset.categoryv1);
		});

		const defaultLink = navContainer.querySelector(".nav-link.active");
		if (defaultLink) {
			filterProducts(defaultLink.dataset.categoryv1 || "all");
		}
	});
</script>

<style>
</style>