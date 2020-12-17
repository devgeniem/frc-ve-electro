<?php

namespace VE\Electro\Product;

use VE\Electro\Models\Product as ProductModel;

class ProductRepository
{
    protected function get(int $id)
    {
        return $this->query([
            'p' => $id,
        ])->first();
    }

    protected function query(array $args = [])
    {
        $results = ProductModel::query($args);

        return $results->map(function(ProductModel $model) {
            return Factory::product($model->payload());
        });
    }

    public function delete($ids = [])
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

    public function import($response)
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

    public function purge($response)
    {
        $purged = [];

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
            $purged[] = $product->post_title;
        }

        return $purged;
    }

    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }
}
