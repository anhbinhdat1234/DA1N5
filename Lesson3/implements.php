<?php

interface AnimalInterface
{
    public function sound();
    public function move();
}

class Cat implements AnimalInterface
{
    // Cài đặt các phương thức của interface
    public function sound()
    {
        echo "Meow!";
    }

    public function move()
    {
        echo "Cat is moving.";
    }
}

$cat = new Cat();
$cat->sound();
$cat->move();
