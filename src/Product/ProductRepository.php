<?php

namespace VE\Electro\Product;

use VE\Electro\EnerimCIS\Code;
use VE\Electro\Models\Product as ProductModel;
use VE\Electro\EnerimCIS;

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

    public static function delete($ids = [])
    {
        if ($ids) {
            $products = ProductModel::query([
                'post_name__in' => $ids,
            ]);
        }

        if (! $ids) {
            $products = ProductModel::all();
        }

        foreach($products as $product) {
            $product->trash();
        }
    }

    public static function import($response)
    {
        $imported = [];
        foreach($response as $payload) {
            $imported[] = $payload['product_name'];
            $id = ProductModel::updateOrCreate([
                'post_title' => $payload['product_name'],
                'post_status' => 'publish',
                'meta_input' => [
                    'payload' => $payload,
                ],
            ]);
        }

        return $imported;
    }

    public static function purge($response)
    {
        $productsFromAPI = collect($response)
            ->pluck('product_name')
            ->toArray();

        $productsFromDB = ProductModel::all();

        $missing = $productsFromDB
            ->map(function($product) {
                return $product->post_title;
            })
            ->diff($productsFromAPI)
            ->toArray();

        $productsToDelete = $productsFromDB
            ->filter(function($product) use($missing) {
                return in_array($product->post_title, $missing);
            });

        foreach($productsToDelete as $product) {
            $product->trash();
        }
    }

    public function __call($name, $arguments)
    {
        return static::$name(...$arguments);
    }
}
