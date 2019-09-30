<?php

namespace VE\Electro\Product\Variations;

class Spot extends Standard
{
    public function getType()
    {
        return 'spot';
    }

    protected function mutateComponents($items)
    {
        return $items->filter(function($item) {
            return (
                $item['unit_price'] != 0 && 
                $item['price_valid_period'] == 0
            );
        });
    }
}
