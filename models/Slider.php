<?php
class Slider extends BaseModel {
    protected $table = 'sliders';

    public function all() {
        $sql = "SELECT * FROM {$this->table} ORDER BY sort_order ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (title, subtitle, link, image_url, sort_order)
                VALUES (:title, :subtitle, :link, :image_url, :sort_order)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':title'      => $data['title'] ?? '',
            ':subtitle'   => $data['subtitle'] ?? '',
            ':link'       => $data['link'] ?? '',
            ':image_url'  => $data['image_url'] ?? '',
            ':sort_order' => $data['sort_order'] ?? 0,
        ]);
    }


    public function updateById($id, $data) {
        $sql = "UPDATE {$this->table}
                SET title = :title, subtitle = :subtitle, link = :link, image_url = :image_url, sort_order = :sort_order
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':title'      => $data['title'] ?? '',
            ':subtitle'   => $data['subtitle'] ?? '',
            ':link'       => $data['link'] ?? '',
            ':image_url'  => $data['image_url'] ?? '',
            ':sort_order' => $data['sort_order'] ?? 0,
            ':id'         => $id,
        ]);
    }

    public function deleteById($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
