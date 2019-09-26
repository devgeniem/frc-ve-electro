<?php

namespace VE\Electro\Money;

class Currency
{
    protected $code;

    public const SYMBOLS = [
        'EUR' => '€',
        'cent' => 'c',
        'SEK' => 'kr',
        'öre' => 'øre',
    ];

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function getSymbol()
    {
        $code = $this->code;
        $symbols = static::SYMBOLS;

        if ( array_key_exists($code, $symbols) ) {
            return $symbols[$code];
        }

        return $code;
    }

    public function isSubunit()
    {
        return !in_array($this->code, ['EUR', 'SEK']);
    }
}
