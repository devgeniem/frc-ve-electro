<?php

namespace VE\Electro\Product\Filters;

class ProductComponentTypeFilter
{
    public function primary($items)
    {
        return $items->where('sort_order', '>', 1);
    }

    public function secondary($items)
    {
        return $items->where('sort_order', '<=', 1);
    }
}
