<?php

namespace VE\Electro\Product\Variants;

use VE\Electro\Product\Product;

class Standard extends Product
{
    protected $type = 'standard';

    protected $canHavePriceEstimation = true;
}
