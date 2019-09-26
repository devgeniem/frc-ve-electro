<?php

namespace VE\Electro\Vat;

class Vat
{
    protected $rate;

    public function __construct($rate)
    {
        $this->rate = $rate;
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function ratio()
    {
        return 1 + ($this->getRate() / 100);
    }

    public function addTo($money)
    {
        return $money->multiply($this->ratio());
    }
}
