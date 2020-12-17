<?php

namespace VE\Electro\Product\Variants;

use VE\Electro\Product\Product;

class Tariff extends Product
{
    protected $type = 'tariff';

    protected $hasRelatedPeriodGroup = true;
}
