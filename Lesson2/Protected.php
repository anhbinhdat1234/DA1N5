<?php

class Vehicle
{
    protected $brand = "Toyota";

    protected function showBrand()
    {
        echo $this->brand;
    }
}

class Car extends Vehicle
{
    public function display()
    {
        $this->showBrand();  // Hợp lệ
    }
}

$car = new Car();
// $car->display();  // Output: Toyota
// echo $car->brand;  // Lỗi: Không thể truy cập thuộc tính được bảo vệ từ bên ngoài

