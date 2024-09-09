<?php

class Vehicle
{
    public $brand;
    public function honk()
    {
        echo "Honking!" . PHP_EOL;
    }
}

class Car extends Vehicle
{
    public $model;
    
    public function drive()
    {
        echo "Driving $this->brand $this->model" . PHP_EOL;
    }
}

class Motobike extends Vehicle
{
    public $seri;
    public function drive()
    {
        echo "Driving $this->brand $this->seri" . PHP_EOL;
    }
}

echo '<pre>';

$myCar3 = new Car();
$myCar3->brand = 'Mec';
$myCar3->model = 'C400';

$myCar3->honk();
$myCar3->drive();