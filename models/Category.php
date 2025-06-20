<?php

class Category extends BaseModel
{
    protected $table = 'categories';

    /**
     * Lấy tất cả danh mục
     */
    public function getAll(): array
    {
        return $this->select('*');
    }

    /**
     * Lấy 3 danh mục có sản phẩm mới nhất
     */
    public function getTop3ByLatestProduct(): array
    {
        $sql = "
            SELECT c.name, c.image_url
            FROM {$this->table} c
            JOIN products p ON p.category_id = c.id
            GROUP BY c.id, c.name, c.image_url
            ORDER BY MAX(p.created_at) DESC
            LIMIT 3
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy danh mục kèm số lượng sản phẩm
     */
    public function getAllWithProductCount(): array
    {
        $sql = "
            SELECT c.*, COUNT(p.id) AS product_count
            FROM {$this->table} c
            LEFT JOIN products p ON p.category_id = c.id
            GROUP BY c.id
            ORDER BY c.name
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        $columns = array_keys($data);
        $placeholders = array_map(fn($c) => ":$c", $columns);

        $sql = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ")
                VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = $this->pdo->prepare($sql);

        foreach ($data as $col => $value) {
            $stmt->bindValue(":$col", $value);
        }

        return $stmt->execute();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function updateCategory(int $id, array $data): bool
    {
        $set = [];
        foreach ($data as $k => $v) {
            $set[] = "$k = :$k";
        }
        $sql = "UPDATE {$this->table} SET " . implode(', ', $set) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }

    public function deleteCategory(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

}

