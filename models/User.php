<?php

class User extends BaseModel
{
    protected $table = 'users';

    public function findByEmail(string $email): ?array
    {
        $emailEscaped = addslashes($email);
        $user = $this->find('*', "email = '$emailEscaped'");
        return $user ?: null;
    }
    public function create(array $data): bool
    {
        $columns    = array_keys($data);                
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
        /**
     * Cập nhật profile (name, phone, address)
     */
    public function updateProfile(int $id, array $data): bool
    {
        $sql = "UPDATE {$this->table}
                  SET name    = :name,
                      phone   = :phone,
                      address = :address
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':name'    => $data['name'],
            ':phone'   => $data['phone'],
            ':address' => $data['address'],
            ':id'      => $id,
        ]);
    }
}
