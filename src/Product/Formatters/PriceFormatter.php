<?php

namespace VE\Electro\Product\Formatters;

use NumberFormatter;

class PriceFormatter
{
    protected $billedBy;

    protected $decimals;

    public function billedBy($unit)
    {
        $this->billedBy = $unit;
        return $this;
    }

    public function decimals($value)
    {
        $this->decimals = $value;
        return $this;
    }

    protected function numberFormatter()
    {
        $formatter = new NumberFormatter('fi_FI', NumberFormatter::DECIMAL);
        $formatter->setAttribute(
            NumberFormatter::FRACTION_DIGITS,
            $this->decimals
        );

        return $formatter;
    }

    public function format($amount)
    {
        return $this->numberFormatter()->format($amount);
    }
}
