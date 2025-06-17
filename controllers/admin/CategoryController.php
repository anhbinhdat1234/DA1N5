<?php
require_once 'models/Category.php';

class CategoryController {
    private $categoryModel;

    public function __construct() {
        $this->categoryModel = new Category();
    }

    public function index() {
        $categories = $this->categoryModel->getAll();
        require 'views/admin/categories/index.php';
    }

    public function create() {
        require 'views/admin/categories/create.php';
    }

    public function store() {
        $data = [
            'name' => $_POST['name'],
        ];

        if (!empty($_FILES['image_url']['name'])) {
            $filename = time() . '-' . basename($_FILES['image_url']['name']);
            $target = 'uploads/' . $filename;
            move_uploaded_file($_FILES['image_url']['tmp_name'], $target);
            $data['image_url'] = $target;
        }

        $this->categoryModel->insert($data);
        header('Location: index.php?action=categories-index&success=1');
    }

    public function edit($id) {
        $category = $this->categoryModel->find('*', 'id = :id', ['id' => $id]);
        require 'views/admin/categories/create.php';
    }

    public function update($id) {
        $data = [
            'name' => $_POST['name'],
        ];

        if (!empty($_FILES['image_url']['name'])) {
            $filename = time() . '-' . basename($_FILES['image_url']['name']);
            $target = 'uploads/' . $filename;
            move_uploaded_file($_FILES['image_url']['tmp_name'], $target);
            $data['image_url'] = $target;
        }

        $this->categoryModel->update($data, 'id = :id', ['id' => $id]);
        header('Location: index.php?action=categories-index&success=1');
    }

    public function delete($id) {
        $this->categoryModel->delete('id = :id', ['id' => $id]);
        header('Location: index.php?action=categories-index&success=1');
    }
}
