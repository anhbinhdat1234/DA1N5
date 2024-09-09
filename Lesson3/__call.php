<?php

class MyClass
{
    public function __call($name, $arguments)
    {
        echo "Method '$name' called with arguments: " . implode(', ', $arguments) . "\n";
    }
}

$object = new MyClass();
$object->nonExistentMethod('arg1', 'arg2');
