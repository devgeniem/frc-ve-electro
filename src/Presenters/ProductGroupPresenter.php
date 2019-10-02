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

    protected function price_period()
    {
        return $this->products->first()->price_period;
    }

    protected function hasRelatedPeriodGroup()
    {
        $period = $this->entity->getRelatedPeriodGroup();
        if (! $period) {
            return;
        }
        return (bool) $period->products->first()->components();
    }

    protected function relatedPeriodGroup()
    {
        return $this->entity->getRelatedPeriodGroup();
    }
}
