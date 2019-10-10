<?php

namespace VE\Electro\Product;

use VE\Electro\EnerimCIS\Code;
use VE\Electro\Models\Product as ProductModel;

class ProductRepository
{
    public static function factory($model)
    {
        $context = new Context\Standard();

        if ( $model->payload->get('product_group') === Code::GROUP_PACKAGE ) {
            $context = new Context\Package();
        }

        elseif ( $model->payload->get('protection_method') === Code::TYPE_TEMPORARY ) {
            $context = new Context\Temporary();
        }

        elseif ( $model->payload->get('protection_method') === Code::TYPE_TARIFF ) {
            $context = new Context\Tariff();
        }

        elseif ( $model->payload->get('protection_method') === Code::TYPE_SPOT ) {
            $context = new Context\Spot();
        }

        return new Product($model, $context);
    }

    public static function get(int $id)
    {
        return static::query([
            'p' => $id,
        ])->first();
    }

    public static function query(array $args = [])
    {
        $results = ProductModel::query($args);

        $results = $results->map(function($item) {
            return static::factory($item);
        });

        return $results;
    }
}
