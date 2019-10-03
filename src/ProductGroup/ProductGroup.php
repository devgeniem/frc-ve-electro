<?php

namespace VE\Electro\ProductGroup;

use VE\Electro\Presenters;
use VE\Electro\EnerimCIS\Code;

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

        $this->products = $this->model->products;

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

    public function getType()
    {
        return $this->model->products->first()->getType();
    }

    public function isType($type)
    {
        return $this->getType() == $type;
    }

    public function getRelatedPeriodGroup()
    {
        if ( $this->isType('temporary') && $this->args('period') == 'primary' ) {
            return new static($this->model, [
                'period' => 'secondary',
            ]);
        }
    }

    public function hasRelatedGroup()
    {
        $group = $this->getRelatedGroup();
        $has = $group->model->products;
        return $has;
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
