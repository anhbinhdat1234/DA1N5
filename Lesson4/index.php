<?php

require_once './Product.php';
require_once './CartItem.php';
require_once './Cart.php';

echo '<pre>';

// Tạo sản phẩm
$product1 = new Product(1, "Sản phẩm A", 100000);
$product2 = new Product(2, "Sản phẩm B", 200000);

// Tạo giỏ hàng
$cart = new Cart();
$cart->addProduct($product1, 2);  // Thêm 2 sản phẩm A
$cart->addProduct($product1, 3);
$cart->addProduct($product2, 1);  // Thêm 1 sản phẩm B

// Hiển thị giỏ hàng
$cart->displayCart();

// Xóa sản phẩm B khỏi giỏ
$cart->removeProduct(2);

// Hiển thị giỏ hàng sau khi xóa
$cart->displayCart();