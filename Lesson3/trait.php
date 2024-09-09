<?php

trait Logger {
    public function log($message) {
        echo "Logging message: $message";
    }
}

class User {
    use Logger; // Sử dụng trait Logger

    public function createUser() {
        $this->log("User created.");
    }
}

class Order {
    use Logger; // Sử dụng lại trait Logger

    public function addOrder() {
        $this->log("Order added.");
    }
}

$user = new User();
$user->createUser();

$product = new Order();
$product->addOrder();