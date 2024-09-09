<?php

class Person
{
    private $name;

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}

$person = new Person();
$person->setName("John Doe");
echo $person->getName();  // Output: John Doe

