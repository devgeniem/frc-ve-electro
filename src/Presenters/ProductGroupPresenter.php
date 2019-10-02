<?php

namespace VE\Electro\Presenters;

class ProductGroupPresenter extends Presenter
{
    protected function products()
    {
        $products = $this->entity->products;
        return $products->map(function($entity) {
            return new ProductPresenter($entity);
        });
    }

    protected function periods()
    {
        return $this->entity->periods();
    }
}
