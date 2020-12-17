<?php

namespace VE\Electro\Product\Variants;

use VE\Electro\Product\Product;
use VE\Electro\Support\Str;

class Additional extends Product
{
    protected $type = 'additional';

    public function componentFilter($component)
    {
        if (Str::startsWith($component->id(), ['UUSIUTUVA'])) {
            return true;
        }

        return ! Str::endsWith($component->id(), ['-E', '-PM', '-MG']);
    }
}
