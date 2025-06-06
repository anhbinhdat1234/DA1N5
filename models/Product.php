<?php

class Product extends BaseModel
{
    protected $table = 'products';


    public function newArrivalsProduct(int $limit = 6, int $categoryId = null): array
    {
        $sql = "
        SELECT 
            p.*,
            MIN(pi.image_url) AS image_url,
            GROUP_CONCAT(DISTINCT pv.color) AS colors,
            GROUP_CONCAT(DISTINCT pv.size) AS sizes
        FROM {$this->table} p
        LEFT JOIN product_images pi ON pi.product_id = p.id
        LEFT JOIN product_variants pv ON pv.product_id = p.id
        " . ($categoryId ? "WHERE p.category_id = :cid" : "") . "
        GROUP BY p.id
        ORDER BY p.created_at DESC
        LIMIT :lim
    ";
        $stmt = $this->pdo->prepare($sql);
        if ($categoryId) {
            $stmt->bindValue(':cid', $categoryId, PDO::PARAM_INT);
        }
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($products as &$product) {
            $product['colors'] = isset($product['colors']) ? explode(',', $product['colors']) : [];
            $product['sizes'] = isset($product['sizes']) ? explode(',', $product['sizes']) : [];
        }

        return $products;
    }

public function getProductByCategoryId(?int $categoryId = null, int $limit = 10): array
{
    $sql = "
        SELECT 
            p.*,
            MIN(pi.image_url) AS image_url,
            GROUP_CONCAT(DISTINCT pv.color ORDER BY pv.color) AS colors,
            GROUP_CONCAT(DISTINCT pv.size ORDER BY pv.size) AS sizes
        FROM {$this->table} p
        LEFT JOIN product_images pi ON pi.product_id = p.id
        LEFT JOIN product_variants pv ON pv.product_id = p.id
    ";

    // Nếu có category thì thêm WHERE
    if ($categoryId !== null) {
        $sql .= " WHERE p.category_id = :cid";
    }

    $sql .= "
        GROUP BY p.id
        ORDER BY p.created_at DESC
        LIMIT :lim
    ";

    $stmt = $this->pdo->prepare($sql);

    if ($categoryId !== null) {
        $stmt->bindValue(':cid', $categoryId, PDO::PARAM_INT);
    }

    $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
    $stmt->execute();

    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as &$product) {
        $product['colors'] = isset($product['colors']) ? explode(',', $product['colors']) : [];
        $product['sizes'] = isset($product['sizes']) ? explode(',', $product['sizes']) : [];
    }

    return $products;
}


    public function getProductDetail(int $productId): ?array
    {
        $sql = "
        SELECT 
            p.*,
            MIN(pi.image_url) AS image_url
        FROM {$this->table} p
        LEFT JOIN product_images pi ON pi.product_id = p.id
        WHERE p.id = :pid
        GROUP BY p.id
        LIMIT 1
    ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':pid', $productId, PDO::PARAM_INT);
        $stmt->execute();

        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product)
            return null;

        $variantSql = "
        SELECT color, size, stock, id
        FROM product_variants
        WHERE product_id = :pid
    ";
        $variantStmt = $this->pdo->prepare($variantSql);
        $variantStmt->bindValue(':pid', $productId, PDO::PARAM_INT);
        $variantStmt->execute();
        $variants = $variantStmt->fetchAll(PDO::FETCH_ASSOC);

        $colors = [];
        $sizes = [];

        foreach ($variants as $variant) {
            if (!in_array($variant['color'], $colors)) {
                $colors[] = $variant['color'];
            }
            if (!in_array($variant['size'], $sizes)) {
                $sizes[] = $variant['size'];
            }
        }
        $product['colors'] = $colors;
        $product['sizes'] = $sizes;
        $product['variants'] = $variants;

        return $product;
    }
public function searchByKeyword(string $keyword): array
{
    $sql = "
        SELECT 
            p.*,
            (
                SELECT image_url
                FROM product_images
                WHERE product_id = p.id
                LIMIT 1
            ) AS thumbnail
        FROM {$this->table} p
        WHERE (p.name LIKE :kw OR p.description LIKE :kw)
        ORDER BY p.created_at DESC
    ";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        ':kw' => '%' . $keyword . '%'
    ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}