<?php

class Product extends BaseModel
{
    protected $table = 'products';

    public function getTop3Latest() {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC LIMIT 3";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll();
    }
}