<?php

namespace VE\Electro\Product\Context;

use Carbon\Carbon;

class Spot extends Standard
{
    public const TYPE = 'spot';

    public function components($components)
    {
        return $components->filter(function($item) {
            return (
                $item['unit_price'] != 0 &&
                $item['price_valid_period'] == 0
            );
        });
    }
}
