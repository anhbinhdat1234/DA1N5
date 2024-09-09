<?php

class MyClass
{
    public function __construct(public $name)
    {
        echo "Object created. Name: " . $this->name . "\n";
    }
}

$object = new MyClass("John");
