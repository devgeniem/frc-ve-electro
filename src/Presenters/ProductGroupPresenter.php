<?php

namespace VE\Electro\Presenters;

class ProductGroupPresenter extends Presenter
{
    protected function products()
    {
        $products = $this->entity->products;
        return $products
            ->sortBy(function($entity) {
                return $entity->getProductName();
            })
            ->map(function($entity) {
                return $entity->present();
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

        $comp = $period->products->first()->components();
        if (! $comp) {
            return;
        }

        return (bool) $comp->all();
    }

    protected function relatedPeriodGroup()
    {
        return $this->entity->getRelatedPeriodGroup();
    }

    public function isType($type)
    {
        return $this->entity->isType($type);
    }

    public function getType()
    {
        return $this->entity->getType();
    }
}
