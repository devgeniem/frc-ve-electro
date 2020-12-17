<?php

namespace VE\Electro\ProductGroup;

use VE\Electro\Presenters\Traits\HasPresenter;
use VE\Electro\Presenters\ProductGroupPresenter;

class ProductGroup
{
    use HasPresenter;

    protected $presenter = ProductGroupPresenter::class;

    protected $attributes;

    public function __construct($attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function products()
    {
        return collect($this->attributes['products'] ?? []);
    }
}
