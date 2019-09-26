<?php

namespace VE\Electro\Vat;

class VatRepository
{
    public static function get($id)
    {
        $value = 0;

        $items = [
            'zero' => 0,
            'standard' => 24,
            'reduced' => 14,
            'super_reduced' => 10,
        ];

        if (array_key_exists($id, $items)) {
            $value = $items[$id];
        }

        return new Vat($value);
    }
}
