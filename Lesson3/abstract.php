<?php

abstract class AnimalMoon
{
    // Phương thức trừu tượng, không có phần thân
    abstract public function sound();

    public function sleep()
    {
        echo "Sleeping...";
    }
}

class DogMoon extends AnimalMoon
{
    // Cài đặt phương thức trừu tượng
    public function sound()
    {
        echo "Bark!";
    }
}

$dog = new DogMoon();
$dog->sound();
$dog->sleep();
