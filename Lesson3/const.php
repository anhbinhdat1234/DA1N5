<?php

class MyClass
{
    const PI = 3.14159;

    public function getPi()
    {
        return self::PI; // Gọi hằng số
    }
}

$myClass = new MyClass();
echo $myClass->getPi();
