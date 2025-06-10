<?php
class Order extends BaseModel
{
protected $table = 'orders';

    public function getAll()
    {
        $sql = "SELECT o.*, u.name as user_name
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            ORDER BY o.id DESC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }


    public function findById($id)
{
$stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
$stmt->execute(['id' => $id]);
return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function deleteById($id)
{
$stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = :id");
return $stmt->execute(['id' => $id]);
}


public function updateStatus($id, $status)
{
$stmt = $this->pdo->prepare("UPDATE {$this->table} SET status = :status WHERE id = :id");
return $stmt->execute(['status' => $status, 'id' => $id]);
}
}