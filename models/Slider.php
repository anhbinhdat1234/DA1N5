<?php
require_once __DIR__ . '/BaseModel.php';

class Slider extends BaseModel {
    protected $table = 'sliders';

    public function all() {
        return $this->select('*', null, [], 'ORDER BY sort_order ASC');
    }

    public function findById($id) {
        return $this->find('*', 'id = :id', ['id' => $id]);
    }

    public function create($data) {
        return $this->insert($data);
    }

    public function updateById($id, $data) {
        return $this->update($data, 'id = :id', ['id' => $id]);
    }

    public function deleteById($id) {
        return $this->delete('id = :id', ['id' => $id]);
    }
}