    <?php

    class HomeController
    {
        public function index()
        {
            //Category
            $categoryModel = new Category();
            $categories = $categoryModel->select();
            //Moi nhat
            $productModel = new Product();
            $newArrivals = $productModel->newArrivalsProduct(8);
            // //Slider
            $sliderModel = new Slider();
            $sliders = $sliderModel->allOrdered();
            // var_dump($sliders);
            //Categories
            $categoryModel = new Category();

            require_once PATH_VIEW_CLIENT . 'partials/header.php';
            require_once PATH_VIEW_CLIENT . 'home.php';
            require_once PATH_VIEW_CLIENT . 'partials/footer.php';
        }
    }