<?php

namespace VE\Electro\Product\Variants;

use VE\Electro\Product\Enums\ComponentType;
use VE\Electro\Product\Enums\FeeType;
use VE\Electro\Product\Product;

class Package extends Product
{
    protected $type = 'package';

    protected $canHavePriceEstimation = false;

    public function componentType($component)
    {
        if ($component->isFeeType(FeeType::MONTHLY)) {
            return ComponentType::PRIMARY;
        }

        return ComponentType::SECONDARY;
    }
}
