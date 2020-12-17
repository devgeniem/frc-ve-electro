<?php

namespace VE\Electro\Product\Traits;

trait HasAdditionalProducts
{
    protected $additionalProducts = [];

    public function addAdditionalProducts($ids)
    {
        $this->additionalProducts = $ids;

        return $this;
    }

    public function additionalProducts()
    {
        return $this->additionalProducts;
    }
}
