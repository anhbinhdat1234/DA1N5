<?php

if (!function_exists('debug')) {
    function debug($data)
    {
        echo '<pre>';
        print_r($data);
        die;
    }
}

if (!function_exists('upload_file')) {
    function upload_file($folder, $file)
    {
        $targetFile = $folder . '/' . time() . '-' . $file["name"];

        if (move_uploaded_file($file["tmp_name"], PATH_ASSETS_UPLOADS . $targetFile)) {
            return $targetFile;
        }

        throw new Exception('Upload file không thành công!');
    }
}

// debug code nha :V
if (!function_exists('dd')) {
    function dd($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        die;
    }
}

// lấy danh sách danh mục
if (!function_exists('get_categories')) {
    function get_categories()
    {
        $categoryModel = new Category();
        return $categoryModel->getAll();
    }
}

// chuyển tên màu sang mã màu
if (!function_exists('get_color_code')) {
    function get_color_code($colorName)
    {
        $colors = [
            'đen' => '#000000',
            'trắng' => '#FFFFFF',
            'xám' => '#808080',
            'xám nhạt' => '#D3D3D3',
            'xám đậm' => '#505050',

            'đỏ' => '#FF0000',
            'đỏ đô' => '#8B0000',
            'đỏ tươi' => '#E32636',
            'hồng' => '#FFC0CB',
            'hồng cánh sen' => '#FF69B4',
            'hồng pastel' => '#FFD1DC',

            'cam' => '#FFA500',
            'cam đất' => '#D2691E',
            'cam cháy' => '#FF7043',

            'vàng' => '#FFFF00',
            'vàng chanh' => '#EFFF00',
            'vàng kem' => '#FFFACD',
            'be' => '#F5F5DC',

            'nâu' => '#8B4513',
            'nâu đất' => '#A0522D',
            'nâu nhạt' => '#DEB887',

            'xanh lá' => '#00FF00',
            'xanh lá cây' => '#228B22',
            'xanh rêu' => '#556B2F',
            'xanh mint' => '#98FF98',

            'xanh dương' => '#0000FF',
            'xanh da trời' => '#87CEEB',
            'xanh navy' => '#000080',
            'xanh coban' => '#0047AB',
            'xanh pastel' => '#AEC6CF',

            'tím' => '#800080',
            'tím than' => '#4B0082',
            'tím pastel' => '#D8BFD8',

            'kem' => '#FFFDD0',
            'ngọc lam' => '#40E0D0',
            'mận' => '#70193D',
            'olive' => '#808000',

            // thêm màu nếu cần
        ];

        return $colors[mb_strtolower($colorName, 'UTF-8')] ?? '#000000';
    }
    if (!function_exists('current_user')) {
    function current_user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }
}

// Lấy role của user
if (!function_exists('current_user_role')) {
    function current_user_role(): string
    {
        return $_SESSION['user']['role'] ?? 'guest';
    }
}

// Kiểm tra đã login
if (!function_exists('is_logged_in')) {
    function is_logged_in(): bool
    {
        return !empty($_SESSION['user']);
    }
}

// Kiểm tra có phải admin
if (!function_exists('is_admin')) {
    function is_admin(): bool
    {
        return is_logged_in() && current_user_role() === 'admin';
    }
}

// Yêu cầu login
if (!function_exists('require_login')) {
    function require_login(): void
    {
        if (!is_logged_in()) {
            header('Location: ' . BASE_URL . '?action=login_form');
            exit;
        }
    }
}

// Yêu cầu role cụ thể
if (!function_exists('require_role')) {
    function require_role(string $role): void
    {
        require_login();
        if (current_user_role() !== $role) {
            header('Location: ' . BASE_URL);
            exit;
        }
    }
}

// Yêu cầu admin
if (!function_exists('require_admin')) {
    function require_admin(): void
    {
        require_role('admin');
    }
}
function orderStatusBadge(string $status): string
{
    return match($status) {
        'pending'    => '<span class="badge bg-warning">Chờ xử lý</span>',
        'processing' => '<span class="badge bg-info">Đang xử lý</span>',
        'shipped'    => '<span class="badge bg-primary">Đã gửi hàng</span>',
        'delivered'  => '<span class="badge bg-success">Đã giao hàng</span>',
        'cancelled'  => '<span class="badge bg-danger">Đã hủy</span>',
        default      => '<span class="badge bg-secondary">'.htmlspecialchars($status).'</span>',
    };
}
//TRANGJ THÁI ĐƠN HÀNG PHÍA NGƯỜI DÙNG
function getStatusText($status)
{
    $statuses = [
        'pending' => 'Chờ xử lý',
        'processing' => 'Đang xử lý',
        'completed' => 'Hoàn thành',
        'cancelled' => 'Đã hủy'
    ];
    return $statuses[$status] ?? $status;
}

function getShippingStatusText($status)
{
    $statuses = [
        'pending' => 'Chờ xử lý',
        'shipping' => 'Đang giao hàng',
        'delivered' => 'Đã giao'
    ];
    return $statuses[$status] ?? $status;
}
}
