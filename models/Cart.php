<?php
// models/Cart.php

class Cart extends BaseModel
{
    protected $table = 'cart_items';

    public function addToCart(?int $userId, int $variantId, int $qty = 1): void
    {
        if ($userId !== null) {
            $sqlCheck = "SELECT id, quantity 
                         FROM {$this->table} 
                         WHERE user_id = :uid AND product_variant_id = :vid 
                         LIMIT 1";
            $stmtCheck = $this->pdo->prepare($sqlCheck);
            $stmtCheck->execute([
                ':uid' => $userId,
                ':vid' => $variantId
            ]);
            $existing = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                $newQty = $existing['quantity'] + $qty;
                $sqlUpdate = "UPDATE {$this->table} 
                              SET quantity = :q 
                              WHERE id = :cid";
                $stmtUpdate = $this->pdo->prepare($sqlUpdate);
                $stmtUpdate->execute([
                    ':q'   => $newQty,
                    ':cid' => $existing['id']
                ]);
            } else {
                $sqlInsert = "INSERT INTO {$this->table} (user_id, product_variant_id, quantity) 
                              VALUES (:uid, :vid, :q)";
                $stmtInsert = $this->pdo->prepare($sqlInsert);
                $stmtInsert->execute([
                    ':uid' => $userId,
                    ':vid' => $variantId,
                    ':q'   => $qty
                ]);
            }
        } else {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            if (isset($_SESSION['cart'][$variantId])) {
                $_SESSION['cart'][$variantId] += $qty;
            } else {
                $_SESSION['cart'][$variantId] = $qty;
            }
        }
    }

    public function getCartItems(?int $userId): array
    {
        $items = [];
        if ($userId !== null) {
            $sql = "
                SELECT 
                    ci.id AS cart_id,
                    pv.id AS product_variant_id,
                    p.id AS product_id,
                    p.name AS product_name,
                    p.price AS price,
                    pv.color AS color,
                    pv.size AS size,
                    (
                        SELECT image_url 
                        FROM product_images 
                        WHERE product_id = p.id 
                        LIMIT 1
                    ) AS thumbnail,
                    ci.quantity AS quantity,
                    (p.price * ci.quantity) AS subtotal
                FROM {$this->table} ci
                JOIN product_variants pv ON pv.id = ci.product_variant_id
                JOIN products p ON p.id = pv.product_id
                WHERE ci.user_id = :uid
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':uid' => $userId]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $variantId => $qty) {
                    $sqlDetail = "
                        SELECT 
                            pv.id AS product_variant_id,
                            p.id AS product_id,
                            p.name AS product_name,
                            p.price AS price,
                            pv.color AS color,
                            pv.size AS size,
                            (
                                SELECT image_url 
                                FROM product_images 
                                WHERE product_id = p.id 
                                LIMIT 1
                            ) AS thumbnail
                        FROM product_variants pv
                        JOIN products p ON p.id = pv.product_id
                        WHERE pv.id = :vid
                        LIMIT 1
                    ";
                    $stmtDet = $this->pdo->prepare($sqlDetail);
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

    public function updateQuantity(int $cartId, int $newQty): void
    {
        if ($newQty <= 0) {
            $sqlDel = "DELETE FROM {$this->table} WHERE id = :cid";
            $stmtDel = $this->pdo->prepare($sqlDel);
            $stmtDel->execute([':cid' => $cartId]);
        } else {
            $sqlUpd = "UPDATE {$this->table} SET quantity = :q WHERE id = :cid";
            $stmtUpd = $this->pdo->prepare($sqlUpd);
            $stmtUpd->execute([
                ':q'   => $newQty,
                ':cid' => $cartId
            ]);
        }
    }

    public function removeItem(int $cartId): void
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :cid";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':cid' => $cartId]);
    }

    public function clearCart(int $userId): void
    {
        $sql = "DELETE FROM {$this->table} WHERE user_id = :uid";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':uid' => $userId]);
    }
}
