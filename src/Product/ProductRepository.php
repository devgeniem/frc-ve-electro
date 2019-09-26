<?php

namespace VE\Electro\Product;

use VE\Electro\EnerimCIS\Code;
use VE\Electro\Models\Product;

class ProductRepository
{
    public static function factory($object)
    {
        if ( $object->payload->get('product_group') === Code::GROUP_PACKAGE ) {
            return new Variations\Package($object->payload);
        }

        if ( $object->payload->get('protection_method') === Code::TYPE_TEMPORARY ) {
            return new Variations\Temporary($object->payload);
        }

        return new Variations\Standard($object->payload);
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
