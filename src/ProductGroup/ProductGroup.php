<?php

namespace VE\Electro\ProductGroup;

use VE\Electro\Presenters;
use VE\Electro\EnerimCIS\Code;

class ProductGroup
{
    // public $products;
    protected $model;
    public $args;

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

    public function period($period = 'primary')
    {
        return new static($this->model, [
            'period' => $period,
        ]);
    }

    public function getType()
    {
        return $this->model->products->first()->getType();
    }

    public function isType($type)
    {
        return $this->getType() == $type;
    }

    public function getRelatedGroup()
    {
        if ($this->isType('temporary')) {
            $period = $this->args['period'] == 'primary' ? 'secondary' : 'primary';
            $group = new static($this->model, [
                'period' => $period,
            ]);
            // var_dump($group->products);
        }
    }

    public function hasRelatedGroup()
    {
        $group = $this->getRelatedGroup();
        $has = $group->model->products;
        var_dump($has);
        return $has;
    }

    public function periods()
    {
        return $this->model->products->first()->periods();
    }

    public function present() {
        return new Presenters\ProductGroup($this);
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