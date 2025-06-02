<div class="main_nav_container">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 text-right">
				<div class="logo_container">
					<a href="#">colo<span>shop</span></a>
				</div>
				<nav class="navbar">
					<ul class="navbar_menu">
						<li><a href="#">home</a></li>
						<li><a href="categories.php">categories</a></li>
						<li><a href="#">pages</a></li>
						<li><a href="#">blog</a></li>
						<li><a href="contact.php">contact</a></li>
					</ul>
					<ul class="navbar_user">
						<li><a href="#"><i class="fa fa-search" aria-hidden="true"></i></a></li>
						<li><a href="#"><i class="fa fa-user" aria-hidden="true"></i></a></li>
						<li class="checkout">
							<a href="#">
								<i class="fa fa-shopping-cart" aria-hidden="true"></i>
								<span id="checkout_items" class="checkout_items">2</span>
							</a>
						</li>
					</ul>
					<div class="hamburger_container">
						<i class="fa fa-bars" aria-hidden="true"></i>
					</div>
				</nav>
			</div>
		</div>
	</div>
</div>

</header>

<div class="fs_menu_overlay"></div>
<div class="hamburger_menu">
	<div class="hamburger_close"><i class="fa fa-times" aria-hidden="true"></i></div>
	<div class="hamburger_menu_content text-right">
		<ul class="menu_top_nav">
			<li class="menu_item has-children">
				<a href="#">
					usd
					<i class="fa fa-angle-down"></i>
				</a>
				<ul class="menu_selection">
					<li><a href="#">cad</a></li>
					<li><a href="#">aud</a></li>
					<li><a href="#">eur</a></li>
					<li><a href="#">gbp</a></li>
				</ul>
			</li>
			<li class="menu_item has-children">
				<a href="#">
					English
					<i class="fa fa-angle-down"></i>
				</a>
				<ul class="menu_selection">
					<li><a href="#">French</a></li>
					<li><a href="#">Italian</a></li>
					<li><a href="#">German</a></li>
					<li><a href="#">Spanish</a></li>
				</ul>
			</li>
			<li class="menu_item has-children">
				<a href="#">
					My Account
					<i class="fa fa-angle-down"></i>
				</a>
				<ul class="menu_selection">
					<li><a href="/client.php?action=login"><i class="fa fa-sign-in" aria-hidden="true"></i>Sign In</a></li>
					<li><a href="/client.php?action=register"><i class="fa fa-user-plus" aria-hidden="true"></i>Register</a></li>
				</ul>
			</li>
			<li class="menu_item"><a href="#">home</a></li>
			<li class="menu_item"><a href="#">shop</a></li>
			<li class="menu_item"><a href="#">promotion</a></li>
			<li class="menu_item"><a href="#">pages</a></li>
			<li class="menu_item"><a href="#">blog</a></li>
			<li class="menu_item"><a href="#">contact</a></li>
		</ul>
	</div>
</div>

<!-- Slider -->

<div class="main_slider" style="background-image:url(images/slider_1.jpg)">
	<div class="container fill_height">
		<div class="row align-items-center fill_height">
			<div class="col">
				<div class="main_slider_content">
					<h6>Spring / Summer Collection 2017</h6>
					<h1>Get up to 30% Off New Arrivals</h1>
					<div class="red_button shop_now_button"><a href="#">shop now</a></div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Banner -->
<style>

/* Toàn bộ khối banner */
.banner {
  padding: 60px 0;              /* Khoảng cách trên/dưới */
  background-color: #f8f8f8;    /* Màu nền (tùy chỉnh) */
}

/* Trong container, mỗi .col-md-4 chứa .banner_item */
.banner .row {
  display: flex;
  flex-wrap: wrap;
  margin: 0 -15px; /* reset margin nếu đã dùng Bootstrap */
}

/* Thẻ chứa ảnh nền cho mỗi category */
.banner_item {
  position: relative;
  width: 100%;
  height: 300px;                /* Chiều cao cố định, bạn có thể chỉnh cao thấp */
  background-position: center;
  background-size: cover;
  background-repeat: no-repeat;
  margin-bottom: 30px;          /* Khoảng cách giữa hàng dọc nếu wrap xuống hàng sau */
  overflow: hidden;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  border-radius: 8px;           /* Bo góc cho đẹp */
  box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Hiệu ứng khi hover vào từng banner_item */
.banner_item:hover {
  transform: scale(1.02);
  box-shadow: 0 8px 16px rgba(0,0,0,0.15);
}

/* Lớp overlay bán trong suốt để chữ nổi bật */
.banner_item::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.35); /* mờ tối để chữ nổi */
  transition: background-color 0.3s ease;
}

/* Khi hover overlay mờ hơn hoặc nhạt hơn tùy ý */
.banner_item:hover::before {
  background-color: rgba(0, 0, 0, 0.25);
}

/* Phần hiển thị tên danh mục */
.banner_category {
  position: absolute;
  bottom: 20px;                 /* Cách đáy 20px */
  left: 20px;                   /* Cách trái 20px */
  color: #ffffff;
  font-size: 24px;              /* Cỡ chữ tùy chỉnh */
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 1px;
  z-index: 2;                   /* Đảm bảo nổi trên overlay */
  text-shadow: 1px 1px 4px rgba(0,0,0,0.5);
}

/* Đảm bảo responsive trên các màn hình nhỏ */
@media (max-width: 767.98px) {
  .banner_item {
    height: 200px;
    margin-bottom: 20px;
  }
  .banner_category {
    font-size: 18px;
    bottom: 15px;
    left: 15px;
  }
}

</style>
<?php
// Giả sử $topCategories được Controller truyền vào
?>
<div class="banner">
  <div class="container">
    <div class="row">
      <?php foreach ($topCategories as $cat): ?>
        <div class="col-md-4">
          <div class="banner_item"
               style="background-image:url('images/categories/<?php echo htmlspecialchars($cat['image_url']); ?>');">
            <div class="banner_category">
              <?php echo htmlspecialchars($cat['name']); ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>



<!-- New Arrivals -->
<?php
?>
<style>
  /* === New Arrivals Section === */
  .new-arrivals-section {
    background-color: #f9f9f9;
    padding: 1rem 0;
  }
  .new-arrivals-section .section_title.new_arrivals_title h2 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #333;
  }
  /* Filter controls */
  .new-arrivals-section .filter_controls {
    display: inline-flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 1rem;
    padding: 1rem;
    margin-bottom: 2rem;
    list-style: none;
  }
  .new-arrivals-section .filter_controls li {
    cursor: pointer;
    font-weight: 500;
    padding: .5rem 1.5rem;
    border: 1px solid #ddd;
    border-radius: 30px;
    transition: background-color .3s, color .3s, border-color .3s;
    color: #555;
  }
  .new-arrivals-section .filter_controls li.active,
  .new-arrivals-section .filter_controls li:hover {
    background-color: #e74c3c;
    color: #fff;
    border-color: #e74c3c;
  }
  /* Product cards */
  .new-arrivals-section .product-card {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 8px;
    overflow: hidden;
    transition: box-shadow .3s ease;
    display: flex;
    flex-direction: column;
  }
  .new-arrivals-section .product-card:hover {
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  }
  .new-arrivals-section .product-card__img img {
    width: 100%;
    display: block;
    border-bottom: 1px solid #eee;
  }
  .new-arrivals-section .product-card__body {
    padding: 1rem;
    flex: 1;
  }
  .new-arrivals-section .product-card__title {
    font-size: 1rem;
    margin-bottom: .5rem;
    color: #333;
  }
  .new-arrivals-section .product-card__price {
    font-size: 1.1rem;
    font-weight: 700;
    color: #e74c3c;
  }
</style>
<section class="new-arrivals-section">
  <div class="container">
    <!-- Tiêu đề -->
    <div class="row mb-4">
      <div class="col text-center">
        <div class="section_title new_arrivals_title">
          <h2>New Arrivals</h2>
        </div>
      </div>
    </div>

    <!-- Filter controls -->
    <div class="row mb-5">
      <div class="col text-center">
        <ul class="filter_controls">
          <li class="active" data-filter="*">Tất cả</li>
          <?php foreach($categories as $cate): ?>
            <li data-filter=".category-<?= $cate['id'] ?>">
              <?= htmlspecialchars($cate['name']) ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

    <!-- Grid sản phẩm -->
    <div class="row product_grid">
      <?php if(!empty($newArrivals)): ?>
        <?php foreach($newArrivals as $prod): ?>
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4 grid-item category-<?= $prod['category_id'] ?>">
          <div class="product-card h-100">
            <div class="product-card__img">
              <a href="?mode=client&action=single&id=<?= $prod['id'] ?>">
				<img 
				src="<?= htmlspecialchars(BASE_ASSETS_UPLOADS . $prod['image_url']); ?>" 
				alt="<?= htmlspecialchars($prod['name']); ?>"
				>
              </a>
            </div>
            <div class="product-card__body text-center">
              <h5 class="product-card__title">
                <a href="?mode=client&action=single&id=<?= $prod['id'] ?>">
                  <?= htmlspecialchars($prod['name']) ?>
                </a>
              </h5>
              <div class="product-card__price">
                <?= number_format($prod['price'],0,',','.') ?>₫
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center">
          <p>Chưa có sản phẩm mới.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Script lọc client-side -->
  <script>
    document.querySelectorAll('.filter_controls li').forEach(btn => {
      btn.addEventListener('click', function(){
        document.querySelectorAll('.filter_controls li')
                .forEach(el => el.classList.remove('active'));
        this.classList.add('active');
        const filter = this.getAttribute('data-filter');
        document.querySelectorAll('.product_grid .grid-item')
                .forEach(item => {
          if (filter==='*' || item.classList.contains(filter.substring(1))) {
            item.style.display = '';
          } else {
            item.style.display = 'none';
          }
        });
      });
    });
  </script>
</section>


<!-- Deal of the week -->

<div class="deal_ofthe_week">
	<div class="container">
		<div class="row align-items-center">
			<div class="col-lg-6">
				<div class="deal_ofthe_week_img">
					<img src="images/deal_ofthe_week.png" alt="">
				</div>
			</div>
			<div class="col-lg-6 text-right deal_ofthe_week_col">
				<div class="deal_ofthe_week_content d-flex flex-column align-items-center float-right">
					<div class="section_title">
						<h2>Deal Of The Week</h2>
					</div>
					<ul class="timer">
						<li class="d-inline-flex flex-column justify-content-center align-items-center">
							<div id="day" class="timer_num">03</div>
							<div class="timer_unit">Day</div>
						</li>
						<li class="d-inline-flex flex-column justify-content-center align-items-center">
							<div id="hour" class="timer_num">15</div>
							<div class="timer_unit">Hours</div>
						</li>
						<li class="d-inline-flex flex-column justify-content-center align-items-center">
							<div id="minute" class="timer_num">45</div>
							<div class="timer_unit">Mins</div>
						</li>
						<li class="d-inline-flex flex-column justify-content-center align-items-center">
							<div id="second" class="timer_num">23</div>
							<div class="timer_unit">Sec</div>
						</li>
					</ul>
					<div class="red_button deal_ofthe_week_button"><a href="#">shop now</a></div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Best Sellers -->

<div class="best_sellers">
	<div class="container">
		<div class="row">
			<div class="col text-center">
				<div class="section_title new_arrivals_title">
					<h2>Best Sellers</h2>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col">
				<div class="product_slider_container">
					<div class="owl-carousel owl-theme product_slider">

						<!-- Slide 1 -->

						<div class="owl-item product_slider_item">
							<div class="product-item">
								<div class="product discount">
									<div class="product_image">
										<img src="images/product_1.png" alt="">
									</div>
									<div class="favorite favorite_left"></div>
									<div class="product_bubble product_bubble_right product_bubble_red d-flex flex-column align-items-center"><span>-$20</span></div>
									<div class="product_info">
										<h6 class="product_name"><a href="single.php">Fujifilm X100T 16 MP Digital Camera (Silver)</a></h6>
										<div class="product_price">$520.00<span>$590.00</span></div>
									</div>
								</div>
							</div>
						</div>

						<!-- Slide 2 -->

						<div class="owl-item product_slider_item">
							<div class="product-item women">
								<div class="product">
									<div class="product_image">
										<img src="images/product_2.png" alt="">
									</div>
									<div class="favorite"></div>
									<div class="product_bubble product_bubble_left product_bubble_green d-flex flex-column align-items-center"><span>new</span></div>
									<div class="product_info">
										<h6 class="product_name"><a href="single.php">Samsung CF591 Series Curved 27-Inch FHD Monitor</a></h6>
										<div class="product_price">$610.00</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Slide 3 -->

						<div class="owl-item product_slider_item">
							<div class="product-item women">
								<div class="product">
									<div class="product_image">
										<img src="images/product_3.png" alt="">
									</div>
									<div class="favorite"></div>
									<div class="product_info">
										<h6 class="product_name"><a href="single.php">Blue Yeti USB Microphone Blackout Edition</a></h6>
										<div class="product_price">$120.00</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Slide 4 -->

						<div class="owl-item product_slider_item">
							<div class="product-item accessories">
								<div class="product">
									<div class="product_image">
										<img src="images/product_4.png" alt="">
									</div>
									<div class="product_bubble product_bubble_right product_bubble_red d-flex flex-column align-items-center"><span>sale</span></div>
									<div class="favorite favorite_left"></div>
									<div class="product_info">
										<h6 class="product_name"><a href="single.php">DYMO LabelWriter 450 Turbo Thermal Label Printer</a></h6>
										<div class="product_price">$410.00</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Slide 5 -->

						<div class="owl-item product_slider_item">
							<div class="product-item women men">
								<div class="product">
									<div class="product_image">
										<img src="images/product_5.png" alt="">
									</div>
									<div class="favorite"></div>
									<div class="product_info">
										<h6 class="product_name"><a href="single.php">Pryma Headphones, Rose Gold & Grey</a></h6>
										<div class="product_price">$180.00</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Slide 6 -->

						<div class="owl-item product_slider_item">
							<div class="product-item accessories">
								<div class="product discount">
									<div class="product_image">
										<img src="images/product_6.png" alt="">
									</div>
									<div class="favorite favorite_left"></div>
									<div class="product_bubble product_bubble_right product_bubble_red d-flex flex-column align-items-center"><span>-$20</span></div>
									<div class="product_info">
										<h6 class="product_name"><a href="single.php">Fujifilm X100T 16 MP Digital Camera (Silver)</a></h6>
										<div class="product_price">$520.00<span>$590.00</span></div>
									</div>
								</div>
							</div>
						</div>

						<!-- Slide 7 -->

						<div class="owl-item product_slider_item">
							<div class="product-item women">
								<div class="product">
									<div class="product_image">
										<img src="images/product_7.png" alt="">
									</div>
									<div class="favorite"></div>
									<div class="product_info">
										<h6 class="product_name"><a href="single.php">Samsung CF591 Series Curved 27-Inch FHD Monitor</a></h6>
										<div class="product_price">$610.00</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Slide 8 -->

						<div class="owl-item product_slider_item">
							<div class="product-item accessories">
								<div class="product">
									<div class="product_image">
										<img src="images/product_8.png" alt="">
									</div>
									<div class="favorite"></div>
									<div class="product_info">
										<h6 class="product_name"><a href="single.php">Blue Yeti USB Microphone Blackout Edition</a></h6>
										<div class="product_price">$120.00</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Slide 9 -->

						<div class="owl-item product_slider_item">
							<div class="product-item men">
								<div class="product">
									<div class="product_image">
										<img src="images/product_9.png" alt="">
									</div>
									<div class="product_bubble product_bubble_right product_bubble_red d-flex flex-column align-items-center"><span>sale</span></div>
									<div class="favorite favorite_left"></div>
									<div class="product_info">
										<h6 class="product_name"><a href="single.php">DYMO LabelWriter 450 Turbo Thermal Label Printer</a></h6>
										<div class="product_price">$410.00</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Slide 10 -->

						<div class="owl-item product_slider_item">
							<div class="product-item men">
								<div class="product">
									<div class="product_image">
										<img src="images/product_10.png" alt="">
									</div>
									<div class="favorite"></div>
									<div class="product_info">
										<h6 class="product_name"><a href="single.php">Pryma Headphones, Rose Gold & Grey</a></h6>
										<div class="product_price">$180.00</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Slider Navigation -->

					<div class="product_slider_nav_left product_slider_nav d-flex align-items-center justify-content-center flex-column">
						<i class="fa fa-chevron-left" aria-hidden="true"></i>
					</div>
					<div class="product_slider_nav_right product_slider_nav d-flex align-items-center justify-content-center flex-column">
						<i class="fa fa-chevron-right" aria-hidden="true"></i>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Benefit -->

<div class="benefit">
	<div class="container">
		<div class="row benefit_row">
			<div class="col-lg-3 benefit_col">
				<div class="benefit_item d-flex flex-row align-items-center">
					<div class="benefit_icon"><i class="fa fa-truck" aria-hidden="true"></i></div>
					<div class="benefit_content">
						<h6>free shipping</h6>
						<p>Suffered Alteration in Some Form</p>
					</div>
				</div>
			</div>
			<div class="col-lg-3 benefit_col">
				<div class="benefit_item d-flex flex-row align-items-center">
					<div class="benefit_icon"><i class="fa fa-money" aria-hidden="true"></i></div>
					<div class="benefit_content">
						<h6>cach on delivery</h6>
						<p>The Internet Tend To Repeat</p>
					</div>
				</div>
			</div>
			<div class="col-lg-3 benefit_col">
				<div class="benefit_item d-flex flex-row align-items-center">
					<div class="benefit_icon"><i class="fa fa-undo" aria-hidden="true"></i></div>
					<div class="benefit_content">
						<h6>45 days return</h6>
						<p>Making it Look Like Readable</p>
					</div>
				</div>
			</div>
			<div class="col-lg-3 benefit_col">
				<div class="benefit_item d-flex flex-row align-items-center">
					<div class="benefit_icon"><i class="fa fa-clock-o" aria-hidden="true"></i></div>
					<div class="benefit_content">
						<h6>opening all week</h6>
						<p>8AM - 09PM</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Blogs -->

<div class="blogs">
	<div class="container">
		<div class="row">
			<div class="col text-center">
				<div class="section_title">
					<h2>Latest Blogs</h2>
				</div>
			</div>
		</div>
		<div class="row blogs_container">
			<div class="col-lg-4 blog_item_col">
				<div class="blog_item">
					<div class="blog_background" style="background-image:url(images/blog_1.jpg)"></div>
					<div class="blog_content d-flex flex-column align-items-center justify-content-center text-center">
						<h4 class="blog_title">Here are the trends I see coming this fall</h4>
						<span class="blog_meta">by admin | dec 01, 2017</span>
						<a class="blog_more" href="#">Read more</a>
					</div>
				</div>
			</div>
			<div class="col-lg-4 blog_item_col">
				<div class="blog_item">
					<div class="blog_background" style="background-image:url(images/blog_2.jpg)"></div>
					<div class="blog_content d-flex flex-column align-items-center justify-content-center text-center">
						<h4 class="blog_title">Here are the trends I see coming this fall</h4>
						<span class="blog_meta">by admin | dec 01, 2017</span>
						<a class="blog_more" href="#">Read more</a>
					</div>
				</div>
			</div>
			<div class="col-lg-4 blog_item_col">
				<div class="blog_item">
					<div class="blog_background" style="background-image:url(images/blog_3.jpg)"></div>
					<div class="blog_content d-flex flex-column align-items-center justify-content-center text-center">
						<h4 class="blog_title">Here are the trends I see coming this fall</h4>
						<span class="blog_meta">by admin | dec 01, 2017</span>
						<a class="blog_more" href="#">Read more</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Newsletter -->

<div class="newsletter">
	<div class="container">
		<div class="row">
			<div class="col-lg-6">
				<div class="newsletter_text d-flex flex-column justify-content-center align-items-lg-start align-items-md-center text-center">
					<h4>Newsletter</h4>
					<p>Subscribe to our newsletter and get 20% off your first purchase</p>
				</div>
			</div>
			<div class="col-lg-6">
				<form action="post">
					<div class="newsletter_form d-flex flex-md-row flex-column flex-xs-column align-items-center justify-content-lg-end justify-content-center">
						<input id="newsletter_email" type="email" placeholder="Your email" required="required" data-error="Valid email is required.">
						<button id="newsletter_submit" type="submit" class="newsletter_submit_btn trans_300" value="Submit">subscribe</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>