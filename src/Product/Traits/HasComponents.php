<?php

namespace VE\Electro\Product\Traits;

use VE\Electro\Product\Enums\ComponentType;
use VE\Electro\Product\Enums\FeeType;
use VE\Electro\Product\ProductComponent;

trait HasComponents
{
    public function components()
    {
        return $this->payload->get('product_components')
            ->map(function($payload) {
                return new ProductComponent($payload, $this);
            })->filter(function($component) {
                return $this->componentFilter($component);
            });
    }

    public function componentFilter($component)
    {
        return true;
    }

    public function componentType($component)
    {
        if ($component->isFeeType(FeeType::ENERGY)) {
            return ComponentType::PRIMARY;
        }

        return ComponentType::SECONDARY;
    }

    public function componentMeta($component)
    {
        return;
    }
}
