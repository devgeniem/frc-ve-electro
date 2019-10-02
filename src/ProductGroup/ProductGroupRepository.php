<?php

namespace VE\Electro\ProductGroup;

use VE\Electro\Models;

class ProductGroupRepository
{
    public static function get($id)
    {
        return static::query([
            'p' => $id,
        ])->first();
    }

    public static function query(array $args = [])
    {
        $results = Models\ProductGroup::query($args);

        $results = $results->map(function($model) {
            return new ProductGroup($model);
        });

        return $results;
    }
}