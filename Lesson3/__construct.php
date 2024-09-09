<?php

class MyClass
{
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
        echo "Object created. Name: " . $this->name . "\n";
    }
}

$object = new MyClass("John");
