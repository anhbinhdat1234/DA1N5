<?php

class MyClass
{
    public static $count = 0;

    public static function increment()
    {
        self::$count++;
    }
}

MyClass::increment();
echo MyClass::$count;