<?php

namespace VE\Electro\Vat;

class VatRepository
{
    protected const RATES = [
        'zero' => 0,
        'standard' => 24,
        'reduced' => 14,
        'super_reduced' => 10,
    ];

    public static function get($id)
    {
        $value = 0;

        if (array_key_exists($id, static::RATES)) {
            $value = static::RATES[$id];
        }

        return new Vat($value);
    }
}
