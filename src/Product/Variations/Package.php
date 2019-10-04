<?php

namespace VE\Electro\Product\Variations;

class Package extends Standard
{
    public const TYPE = 'package';

    public function getMeasurementMethodName()
    {
        return $this->getDescription();
    }

    public function components()
    {
        return $this->components->filter(function($item) {
            return $item['sort_order'] != 2;
        })->map(function($item) {
            $item['sort_order'] = 2;
            return $item;
        });
    }
}
