<?php

namespace VE\Electro\Money;

class Currency
{
    protected $code;

    protected $symbol;

    protected $subunit = false;

    protected const CURRENCIES = [
        'EUR' => [
            'symbol' => '€',
            'subunit' => false,
        ],
        'cent' => [
            'symbol' => 'c',
            'subunit' => true,
        ],
        'SEK' => [
            'symbol' => 'kr',
            'subunit' => false,
        ],
        'öre' => [
            'symbol' => 'øre',
            'subunit' => true,
        ],
    ];

    public function __construct($code)
    {
        $this->code = $code;

        $currencies = static::CURRENCIES;

        if (array_key_exists($code, $currencies)) {
            $this->symbol = $currencies[$code]['symbol'];
            $this->subunit = $currencies[$code]['subunit'];
        } else {
            $this->symbol = $code;
        }

    }

    public function getSymbol()
    {
        return $this->symbol;
    }

    public function isSubunit()
    {
        return $this->subunit;
    }
}
