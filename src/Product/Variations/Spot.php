<?php

namespace VE\Electro\Product\Variations;

class Spot extends Standard
{
    public const TYPE = 'spot';

    public function components()
    {
        return $this->components
            ->period($this->getFilter('period'))
            ->filter(function($item) {
                return (
                    $item['unit_price'] != 0 &&
                    $item['price_valid_period'] == 0
                );
            });
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
