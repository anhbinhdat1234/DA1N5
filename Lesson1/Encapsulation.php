<?php

class BankAccount
{
    private $balance = 0;

    public function deposit($amount)
    {
        $this->balance += $amount;
    }

    public function getBalance()
    {
        return $this->balance;
    }
}

$account = new BankAccount();

$account->deposit(100);

$account->deposit(500);

echo $account->getBalance();  // Output: 100