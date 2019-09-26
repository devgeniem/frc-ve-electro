<?php

namespace VE\Electro\Product\Variations;

class Package extends Standard
{
    public function getMeasurementMethodName()
    {
        return $this->getDescription();
    }

    protected function mutateComponents($items)
    {
        $items = $items->filter(function($item) {
            return $item['sort_order'] != 2;
        });

        $items = $items->map(function($item) {
            $item['sort_order'] = 2;
            return $item;
        });

        return $items;
    }
}
