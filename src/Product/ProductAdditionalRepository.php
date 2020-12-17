<?php

namespace VE\Electro\Product;

use VE\Electro\Models\Product as ProductModel;
use VE\Electro\Models\ProductAdditional as ProductAdditionalModel;

class ProductAdditionalRepository
{
    protected function get(int $id)
    {
        return $this->query([
            'p' => $id,
        ])->first();
    }

    protected function all()
    {
        return $this->query([
            'posts_per_page' => 500,
        ]);
    }

    protected function query(array $args = [])
    {
        $results = ProductAdditionalModel::query($args);

        return $results->flatMap(function(ProductAdditionalModel $group) {
            return $group->products()
                ->map(function($item) {
                    $item['product'] = ProductModel::query(['p' => $item['ec_product']])->first();
                    return $item;
                })
                ->filter(function($item) {
                    return $item['product'];
                })
                ->map(function($item) {
                    $item['product'] = new Variants\Additional($item['product'] ->payload());
                    return $item;
                })
                ->map(function($item) use ($group) {
                    $product = $item['product'];
                    unset($item['product'], $item['ec_product']);

                    return $product->addMeta($item)->addMeta([
                        'icon' => $group->meta->icon,
                        'description' => $group->meta->description,
                        'additional_description' => $group->meta->additional_description,
                    ]);
                });
        });
    }

    public static function __callStatic($method, $parameters)
    {
        return (new static)->$method(...$parameters);
    }
}
