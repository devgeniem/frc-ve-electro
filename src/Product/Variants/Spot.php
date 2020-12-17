<?php

namespace VE\Electro\Product\Variants;

use VE\Electro\Product\Enums\ComponentType;
use VE\Electro\Product\Enums\FeeType;
use VE\Electro\Product\Product;
use VE\Electro\Support\Str;

class Spot extends Product
{
    protected $type = 'spot';

    protected $canHavePriceEstimation = false;

    public function componentFilter($component)
    {
        return ! Str::endsWith($component->id(), '-E');
    }

    public function componentMeta($component)
    {
        if (Str::endsWith($component->id(), '-MG')) {
            return 'COMPONENTS.SPOT.META';
        }
    }

    public function componentType($component)
    {
        if ($component->isFeeType(FeeType::MARGINAL)) {
            return ComponentType::PRIMARY;
        }

        return ComponentType::SECONDARY;
    }
}
