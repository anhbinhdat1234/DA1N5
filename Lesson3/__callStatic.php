<?php

class MyClass
{
    public static function __callStatic($name, $arguments)
    {
        echo "Static method '$name' called with arguments: " . implode(', ', $arguments) . "\n";
    }
}

MyClass::nonExistentStaticMethod('arg1', 'arg2');
