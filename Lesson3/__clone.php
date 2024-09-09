<?php

class MyClass
{
    public function __construct(public $name) {}

    public function __clone()
    {
        echo "Object cloned. Original name: " . $this->name . "\n";
    }
}

echo '<pre>';

$object1 = new MyClass("DucTV44");
$object2 = clone $object1;

print_r($object1);
print_r($object2);