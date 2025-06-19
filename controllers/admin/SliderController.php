<?php
require_once 'models/Slider.php';

class SliderController {
    private $sliderModel;

    public function __construct() {
        $this->sliderModel = new Slider();
    }

    public function index() {
        $sliders = $this->sliderModel->all();
        require_once 'views/admin/slides/index.php';
    }

    public function create() {
        require_once 'views/admin/slides/create.php';
    }

    public function store() {
        $data = [
            'title' => $_POST['title'] ?? '',
            'subtitle' => $_POST['subtitle'] ?? '',
            'link' => $_POST['link'] ?? '',
            'sort_order' => $_POST['sort_order'] ?? 0,
        ];


        if (!empty($_FILES['image_url']['name'])) {
            $fileName = time() . '-' . basename($_FILES['image_url']['name']);
            $targetPath = 'uploads/' . $fileName;
            move_uploaded_file($_FILES['image_url']['tmp_name'], $targetPath);
            $data['image_url'] = $targetPath;
        }

        $this->sliderModel->create($data);
        header('Location: index.php?action=sliders-index&success=1');
    }

    public function edit($id) {
        $slider = $this->sliderModel->findById($id);
        require_once 'views/admin/slides/create.php';
    }

    public function update($id) {
        $data = [
            'title' => $_POST['title'],
            'subtitle' => $_POST['subtitle'],
            'link' => $_POST['link'],
            'sort_order' => $_POST['sort_order'],
        ];

        if (!empty($_FILES['image_url']['name'])) {
            $fileName = time() . '-' . basename($_FILES['image_url']['name']);
            $targetPath = 'uploads/' . $fileName;
            move_uploaded_file($_FILES['image_url']['tmp_name'], $targetPath);
            $data['image_url'] = $targetPath;
        }

        $this->sliderModel->updateById($id, $data);
        header('Location: index.php?action=sliders-index&success=1');
    }

    public function delete($id) {
        $this->sliderModel->deleteById($id);
        header('Location: index.php?action=sliders-index&success=1');
    }
}