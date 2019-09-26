<?php

namespace VE\Electro\Money;

class Money
{
    protected $amount;
    protected $currency;

    public function __construct($amount, $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function multiply($value)
    {
        $amount = $this->amount * $value;
        return new static($amount, $this->currency);
    }
}
