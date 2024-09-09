<?php

class BaseClassX
{
    public final function sayHello()
    {
        echo "Hello from BaseClass!";
    }
}

class ChildClass extends BaseClassX
{
    // Gây ra lỗi nếu cố gắng override phương thức sayHello
    public function sayHello() {
        echo "Hello from ChildClass!";
    }
}
