<?php
require_once PATH_MODEL . 'BaseModel.php';

class ProductImage extends BaseModel
{
    protected $table = 'product_images';

    public function findByProductId(int $productId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT image_url FROM {$this->table} WHERE product_id = :pid"
        );
        $stmt->execute([':pid' => $productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        $cols         = array_keys($data);
        $holders      = array_map(fn($c) => ":$c", $cols);
        $sql = "INSERT INTO {$this->table} ("
             . implode(', ', $cols) . ") VALUES ("
             . implode(', ', $holders) . ")";
        $stmt = $this->pdo->prepare($sql);
        foreach ($data as $k => $v) {
            $stmt->bindValue(":$k", $v);
        }
        return $stmt->execute();
    }

    public function deleteByProductId(int $productId): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM {$this->table} WHERE product_id = :pid"
        );
        return $stmt->execute([':pid' => $productId]);
    }
}
