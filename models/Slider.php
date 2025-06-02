<?php

class Slider extends BaseModel
{
    protected $table = 'sliders';

    public function allOrdered(): array
    {
        $sql  = "SELECT * FROM {$this->table} ORDER BY sort_order ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
