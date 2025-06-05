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
}
