<?php
// controllers/admin/DashboardController.php

require_once __DIR__ . '/../../models/Order.php';
require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Category.php';

class DashboardController
{
    protected $orderModel;
    protected $productModel;
    protected $userModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->orderModel = new Order();
        $this->productModel = new Product();
        $this->userModel = new User();
        $this->categoryModel = new Category(); // You'll need to create this model
    }

    /**
     * Display admin dashboard with statistics
     */
    public function index()
    {
        // Check if user is admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /login');
            exit();
        }

        // Get statistics for the last 30 days
        $startDate = date('Y-m-d', strtotime('-30 days'));
        $endDate = date('Y-m-d');

        // Revenue statistics
        $revenueStats = $this->getRevenueStatistics($startDate, $endDate);
        
        // Best selling products
        $bestSellers = $this->getBestSellingProducts($startDate, $endDate);
        
        // New users
        $newUsers = $this->getNewUsers($startDate, $endDate);
        
        // Recent orders
        $recentOrders = $this->getRecentOrders();
        
        // Category sales
        $categorySales = $this->getCategorySales($startDate, $endDate);
        
        // Coupon usage
        $couponUsage = $this->getCouponUsage($startDate, $endDate);
        
        // Order status counts
        $orderStatusCounts = $this->getOrderStatusCounts();
        
        // Stock alerts
        $lowStockProducts = $this->getLowStockProducts(5);

        // Load the view
        require_once __DIR__ . '/../../views/admin/dashboard.php';
    }

    /**
     * Get revenue statistics grouped by day
     */
    protected function getRevenueStatistics(string $startDate, string $endDate): array
    {
        $sql = "
            SELECT 
                DATE(created_at) AS date,
                SUM(total) AS daily_revenue,
                COUNT(id) AS order_count
            FROM orders
            WHERE 
                status != 'cancelled' AND
                created_at BETWEEN :start AND :end
            GROUP BY DATE(created_at)
            ORDER BY date ASC
        ";
        
        $stmt = $this->orderModel->getPdo()->prepare($sql);
        $stmt->execute([
            ':start' => $startDate,
            ':end' => $endDate
        ]);
        
        $dailyData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calculate totals
        $totalRevenue = array_sum(array_column($dailyData, 'daily_revenue'));
        $totalOrders = array_sum(array_column($dailyData, 'order_count'));
        
        return [
            'daily' => $dailyData,
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'average_order_value' => $totalOrders > 0 ? $totalRevenue / $totalOrders : 0
        ];
    }

    /**
     * Get best selling products
     */
    protected function getBestSellingProducts(string $startDate, string $endDate, int $limit = 5): array
    {
        $sql = "
            SELECT 
                p.id,
                p.name,
                SUM(oi.quantity) AS total_sold,
                SUM(oi.price * oi.quantity) AS total_revenue,
                MIN(pi.image_url) AS image_url
            FROM order_items oi
            JOIN orders o ON oi.order_id = o.id
            JOIN product_variants pv ON oi.product_variant_id = pv.id
            JOIN products p ON pv.product_id = p.id
            LEFT JOIN product_images pi ON pi.product_id = p.id
            WHERE 
                o.status != 'cancelled' AND
                o.created_at BETWEEN :start AND :end
            GROUP BY p.id, p.name
            ORDER BY total_sold DESC
            LIMIT :limit
        ";
        
        $stmt = $this->orderModel->getPdo()->prepare($sql);
        $stmt->bindValue(':start', $startDate);
        $stmt->bindValue(':end', $endDate);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get new users
     */
    protected function getNewUsers(string $startDate, string $endDate, int $limit = 5): array
    {
        $sql = "
            SELECT 
                id,
                name,
                email,
                created_at
            FROM users
            WHERE 
                created_at BETWEEN :start AND :end
            ORDER BY created_at DESC
            LIMIT :limit
        ";
        
        $stmt = $this->userModel->getPdo()->prepare($sql);
        $stmt->bindValue(':start', $startDate);
        $stmt->bindValue(':end', $endDate);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get recent orders
     */
    protected function getRecentOrders(int $limit = 5): array
    {
        $sql = "
            SELECT 
                o.id,
                o.total,
                o.status,
                o.created_at,
                u.name AS customer_name,
                s.address AS shipping_address
            FROM orders o
            JOIN users u ON o.user_id = u.id
            LEFT JOIN shippings s ON s.order_id = o.id
            ORDER BY o.created_at DESC
            LIMIT :limit
        ";
        
        $stmt = $this->orderModel->getPdo()->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get sales by category
     */
    protected function getCategorySales(string $startDate, string $endDate): array
    {
        $sql = "
            SELECT 
                c.id,
                c.name,
                COUNT(o.id) AS order_count,
                SUM(o.total) AS total_revenue
            FROM orders o
            JOIN order_items oi ON o.id = oi.order_id
            JOIN product_variants pv ON oi.product_variant_id = pv.id
            JOIN products p ON pv.product_id = p.id
            JOIN categories c ON p.category_id = c.id
            WHERE 
                o.status != 'cancelled' AND
                o.created_at BETWEEN :start AND :end
            GROUP BY c.id, c.name
            ORDER BY total_revenue DESC
        ";
        
        $stmt = $this->orderModel->getPdo()->prepare($sql);
        $stmt->execute([
            ':start' => $startDate,
            ':end' => $endDate
        ]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get coupon usage statistics
     */
    protected function getCouponUsage(string $startDate, string $endDate): array
    {
        $sql = "
            SELECT 
                coupon_code,
                COUNT(id) AS usage_count,
                SUM(discount_amount) AS total_discount
            FROM orders
            WHERE 
                coupon_code IS NOT NULL AND
                created_at BETWEEN :start AND :end
            GROUP BY coupon_code
            ORDER BY usage_count DESC
        ";
        
        $stmt = $this->orderModel->getPdo()->prepare($sql);
        $stmt->execute([
            ':start' => $startDate,
            ':end' => $endDate
        ]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get order status counts
     */
    protected function getOrderStatusCounts(): array
    {
        $sql = "
            SELECT 
                status,
                COUNT(id) AS count
            FROM orders
            GROUP BY status
        ";
        
        $stmt = $this->orderModel->getPdo()->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get products with low stock
     */
    protected function getLowStockProducts(int $threshold = 5): array
    {
        $sql = "
            SELECT 
                p.id,
                p.name,
                pv.size,
                pv.color,
                pv.stock,
                MIN(pi.image_url) AS image_url
            FROM product_variants pv
            JOIN products p ON pv.product_id = p.id
            LEFT JOIN product_images pi ON pi.product_id = p.id
            WHERE pv.stock <= :threshold
            GROUP BY p.id, p.name, pv.size, pv.color, pv.stock
            ORDER BY pv.stock ASC
        ";
        
        $stmt = $this->productModel->getPdo()->prepare($sql);
        $stmt->bindValue(':threshold', $threshold, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}