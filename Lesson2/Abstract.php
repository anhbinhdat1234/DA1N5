<?php

abstract class Animal
{
    abstract public function makeSound();

    public function eat()
    {
        echo "Eating...";
    }
}

class Dog extends Animal
{
    public function makeSound()
    {
        echo "Bark";
    }
}
