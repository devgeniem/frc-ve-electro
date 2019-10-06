<?php

namespace VE\Electro\Product\Collections;

use Carbon\Carbon;
use VE\Electro\Product\Filters;

class ComponentCollection extends PayloadCollection
{
    public function period($method, $context)
    {
        $filter = new Filters\ProductComponentPeriodFilter($this, $context);
        return $filter->$method();
    }

    public function type($method)
    {
        $filter = new Filters\ProductComponentTypeFilter();
        return $filter->$method($this);
    }
}

