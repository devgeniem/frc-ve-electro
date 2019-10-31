<?php

namespace VE\Electro\ProductGroup;

use VE\Electro\Presenters;
use VE\Electro\EnerimCIS\Code;
use VE\Electro\Models\Product;
use VE\Electro\Product\ProductRepository;

class ProductGroup
{
    // public $products;
    protected $model;
    protected $args;

    public function __construct($model, array $args = [])
    {
        $this->model = $model;

        $defaults = [
            'period' => 'primary',
        ];

        $args = wp_parse_args($args, $defaults);
        $this->args = $args;

        $this->products = $this->model->products->filter();

        if (! $this->products->count()) {
            $this->products->push(ProductRepository::factory(new Product));
        }

        if ($args) {
            $this->products = $this->products->map(function($product) use($args) {
                $product = $product->filter($args);
                return $product;
            });
        }

    }

    public function args($key)
    {
        return $this->args[$key] ?? null;
    }

    protected function referenceProduct()
    {
        return $this->products->first();
    }

    public function getType()
    {
        return $this->referenceProduct()->getType();
    }

    public function isType($type)
    {
        return $this->getType() == $type;
    }

    public function getRelatedPeriodGroup()
    {
        if ( $this->hasRelatedPeriodGroup() ) {
            return new static($this->model, [
                'period' => 'secondary',
            ]);
        }
    }

    public function hasRelatedPeriodGroup()
    {
        return $this->referenceProduct()->hasRelatedPeriodGroup() && $this->args('period') == 'primary' ;
    }

    public function periods()
    {
        $periods = $this->model->products->first()->periods();
        return array_map(function($period) {
            return new static($this->model, [
                'period' => $period,
            ]);
        }, $periods);
    }

    public function present() {
        return new Presenters\ProductGroupPresenter($this);
    }

    public function __get($property)
    {
        if ($this->model->meta->$property) {
            return $this->model->meta->$property;
        }

        if ($this->model->$property) {
            return $this->model->$property;
        }
    }
}
