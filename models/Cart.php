<?php
// models/Cart.php
require_once __DIR__ . '/BaseModel.php';

class Cart extends BaseModel
{
    protected $table = 'cart_items';

    /**
     * Thêm vào giỏ, có kiểm tra stock
     *
     * @param int|null $userId     ID user nếu đã login, null nếu guest
     * @param int      $variantId  ID variant sản phẩm
     * @param int      $qty        Số lượng thêm
     * @throws Exception nếu vượt quá stock
     */
    public function addToCart(?int $userId, int $variantId, int $qty = 1): void
    {
        // 1) Lấy stock hiện tại
        $stmtStock = $this->pdo->prepare(
            "SELECT stock FROM product_variants WHERE id = :vid"
        );
        $stmtStock->execute([':vid' => $variantId]);
        $row       = $stmtStock->fetch(PDO::FETCH_ASSOC);
        $available = $row ? (int)$row['stock'] : 0;

        // 2) Tính số đã có trong cart
        $currentInCart = 0;
        if ($userId === null) {
            $currentInCart = $_SESSION['cart'][$variantId] ?? 0;
        } else {
            $stmtC = $this->pdo->prepare(
                "SELECT quantity FROM {$this->table}
                 WHERE user_id = :uid AND product_variant_id = :vid"
            );
            $stmtC->execute([
                ':uid' => $userId,
                ':vid' => $variantId
            ]);
            $c = $stmtC->fetch(PDO::FETCH_ASSOC);
            $currentInCart = $c ? (int)$c['quantity'] : 0;
        }

        // 3) Nếu tổng muốn thêm > stock thì lỗi
        if ($currentInCart + $qty > $available) {
            throw new \Exception("Chỉ còn $available sản phẩm trong kho.");
        }

        // 4) Thêm vào cart
        if ($userId !== null) {
            // với user login: lưu vào DB
            $stmtCheck = $this->pdo->prepare(
                "SELECT id, quantity 
                   FROM {$this->table} 
                  WHERE user_id = :uid 
                    AND product_variant_id = :vid
                  LIMIT 1"
            );
            $stmtCheck->execute([':uid'=>$userId,':vid'=>$variantId]);
            $existing = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                $newQty = $existing['quantity'] + $qty;
                $stmtUpd = $this->pdo->prepare(
                    "UPDATE {$this->table}
                        SET quantity = :q
                      WHERE id = :cid"
                );
                $stmtUpd->execute([':q'=>$newQty,':cid'=>$existing['id']]);
            } else {
                $stmtIns = $this->pdo->prepare(
                    "INSERT INTO {$this->table}
                     (user_id, product_variant_id, quantity)
                     VALUES (:uid, :vid, :q)"
                );
                $stmtIns->execute([
                    ':uid'=>$userId,
                    ':vid'=>$variantId,
                    ':q'=>$qty
                ]);
            }
        } else {
            // với guest: lưu vào $_SESSION
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            $_SESSION['cart'][$variantId] = $currentInCart + $qty;
        }
    }

    /**
     * Lấy các item trong cart (user hoặc guest)
     *
     * @param int|null $userId
     * @return array
     */
    public function getCartItems(?int $userId): array
    {
        $items = [];
        if ($userId !== null) {
            $sql = "
                SELECT 
                    ci.id AS cart_id,
                    pv.id AS product_variant_id,
                    p.id  AS product_id,
                    p.name  AS product_name,
                    p.price AS price,
                    pv.color AS color,
                    pv.size  AS size,
                    (
                        SELECT image_url 
                          FROM product_images 
                         WHERE product_id = p.id 
                         LIMIT 1
                    ) AS thumbnail,
                    ci.quantity AS quantity,
                    (p.price * ci.quantity) AS subtotal
                  FROM {$this->table} ci
                  JOIN product_variants pv 
                    ON pv.id = ci.product_variant_id
                  JOIN products p 
                    ON p.id = pv.product_id
                 WHERE ci.user_id = :uid
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':uid' => $userId]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $variantId => $qty) {
                    $stmtDet = $this->pdo->prepare("
                        SELECT 
                            pv.id           AS product_variant_id,
                            p.id            AS product_id,
                            p.name          AS product_name,
                            p.price         AS price,
                            pv.color        AS color,
                            pv.size         AS size,
                            (
                                SELECT image_url 
                                  FROM product_images 
                                 WHERE product_id = p.id 
                                 LIMIT 1
                            ) AS thumbnail
                          FROM product_variants pv
                          JOIN products p 
                            ON p.id = pv.product_id
                         WHERE pv.id = :vid
                         LIMIT 1
                    ");
                    $stmtDet->execute([':vid' => $variantId]);
                    $det = $stmtDet->fetch(PDO::FETCH_ASSOC);
                    if ($det) {
                        $items[] = [
                            'cart_id'            => $variantId,
                            'product_variant_id' => $variantId,
                            'product_id'         => $det['product_id'],
                            'product_name'       => $det['product_name'],
                            'price'              => $det['price'],
                            'color'              => $det['color'],
                            'size'               => $det['size'],
                            'thumbnail'          => $det['thumbnail'],
                            'quantity'           => $qty,
                            'subtotal'           => $det['price'] * $qty
                        ];
                    }
                }
            }
        }
        return $items;
    }

    /**
     * Cập nhật số lượng
     */
    public function updateQuantity(int $cartId, int $newQty): void
    {
        if ($newQty <= 0) {
            $stmtDel = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :cid");
            $stmtDel->execute([':cid' => $cartId]);
        } else {
            $stmtUpd = $this->pdo->prepare(
                "UPDATE {$this->table} 
                    SET quantity = :q 
                  WHERE id = :cid"
            );
            $stmtUpd->execute([':q'=>$newQty,':cid'=>$cartId]);
        }
    }

    /**
     * Xóa một item
     */
    public function removeItem(int $cartId): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :cid");
        $stmt->execute([':cid' => $cartId]);
    }

    /**
     * Xóa toàn bộ cart
     */
    public function clearCart(?int $userId): void
    {
        if ($userId !== null) {
            $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE user_id = :uid");
            $stmt->execute([':uid' => $userId]);
        }
        unset($_SESSION['cart']);
    }
}
