<?php

class BaseClass
{
    public function sayHello()
    {
        echo "Hello from BaseClass!";
    }
}

class ChildClass extends BaseClass
{
    public function sayHello()
    {
        parent::sayHello(); // Gọi phương thức của lớp cha
        echo " And hello from ChildClass!";
    }
}

$child = new ChildClass();
$child->sayHello(); // Output: Hello from BaseClass! And hello from ChildClass!