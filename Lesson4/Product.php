<?php

class Product
{
    public function __construct(
        private $id,
        private $name,
        private $price = 0
    ) {}

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPrice()
    {
        return $this->price;
    }
}
