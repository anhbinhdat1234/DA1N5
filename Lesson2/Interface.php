<?php

interface Animal
{
    public function makeSound();
}

interface Movable
{
    public function move();
}

class Dog implements Animal, Movable
{
    public function makeSound()
    {
        echo "Bark";
    }

    public function move()
    {
        echo "Running";
    }
}

