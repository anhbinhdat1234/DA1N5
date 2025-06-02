<?php

class HomeController
{
    public function index() 
    {
        //Category
        $categoryModel = new Category();
        $categories    = $categoryModel->select();
        //Moi nhat
        $productModel  = new Product();
        $newArrivals   = $productModel->newArrivals(6);
        // //Slider
        $sliderModel = new Slider();
        $sliders     = $sliderModel->allOrdered();
        // var_dump($sliders);
        //Categories
        $categoryModel = new Category();
        $topCategories = $categoryModel->getTop3ByLatestProduct();

        
        require_once PATH_VIEW_CLIENT . 'partials/header.php';
        require_once PATH_VIEW_CLIENT . 'home.php';
        require_once PATH_VIEW_CLIENT . 'partials/footer.php'; 
    }
}