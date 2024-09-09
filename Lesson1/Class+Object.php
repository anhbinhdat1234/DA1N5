<?php

class Car
{
    public $brand;
    public $color;

    public function drive()
    {
        echo "Driving $this->brand car" . PHP_EOL;
    }
}

echo '<pre>';

$myCar1 = new Car();
$myCar1->brand = "Toyota";
$myCar1->color = "Red";
$myCar1->drive();  // Output: Driving Toyota car

$myCar2 = new Car();
$myCar2->brand = "Mec";
$myCar2->color = "Black";
$myCar2->drive();  // Output: Driving Mec car