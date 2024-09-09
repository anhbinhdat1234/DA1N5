<?php

$action = $_GET['action'] ?? '/';

match ($action) {
    '/'         => (new DashboardController)->index(),
    'test-show' => (new TestController)->show(),

    // CRUD User
    'users-index'    => (new UserController)->index(),   // Hiển thị danh sách
    'users-show'     => (new UserController)->show(),    // Hiển thị chi tiết theo ID
    'users-create'   => (new UserController)->create(),  // Hiển thị form thêm mới
    'users-store'    => (new UserController)->store(),   // Lưu dữ liệu thêm mới
    'users-edit'     => (new UserController)->edit(),    // Hiển thị form cập nhật theo ID
    'users-update'   => (new UserController)->update(),  // Lưu dữ liệu cập nhật theo ID
    'users-delete'   => (new UserController)->delete(),  // Xóa dữ liệu theo ID
};
