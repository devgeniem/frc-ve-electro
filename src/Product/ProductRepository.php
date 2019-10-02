<?php

namespace VE\Electro\Product;

use VE\Electro\EnerimCIS\Code;
use VE\Electro\Models\Product;

class ProductRepository
{
    public static function factory($model)
    {
        if ( $model->payload->get('product_group') === Code::GROUP_PACKAGE ) {
            return new Variations\Package($model);
        }

        if ( $model->payload->get('protection_method') === Code::TYPE_TEMPORARY ) {
            return new Variations\Temporary($model);
        }

        if ( $model->payload->get('protection_method') === Code::TYPE_SPOT ) {
            return new Variations\Spot($model);
        }

        return new Variations\Standard($model);
    }

    public static function get(int $id)
    {
        return static::query([
            'p' => $id,
        ])->first();
    }

    public static function query(array $args = [])
    {
        $results = Product::query($args);

        $results = $results->map(function($item) {
            return static::factory($item);
        });

        return $results;
    }
}
