<?php

class ParentClass
{
    public function greet()
    {
        echo "Hello from ParentClass!";
    }
}

class ChildClass extends ParentClass
{
    // Ghi đè phương thức của lớp cha
    public function greet()
    {
        echo "Hello from ChildClass!";
    }
}

$child = new ChildClass();
$child->greet();
