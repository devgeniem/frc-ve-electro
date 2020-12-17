<?php

namespace VE\Electro\Presenters;

use VE\Electro\ProductGroup\ProductGroup;

class ProductGroupPresenter extends Presenter
{
    protected $group;

    public function __construct(ProductGroup $group)
    {
        $this->group = $group;
    }

    public function data(): array
    {
        return collect([
            'products' => $this->group->products()->map->present(),
        ])->toArray();
    }
}
