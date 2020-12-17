<?php

namespace VE\Electro\ProductGroup;

use VE\Electro\Models\ProductGroup as ProductGroupModel;
use VE\Electro\Product\Product;
use VE\Electro\Product\ProductAdditionalRepository;
use VE\Electro\Product\ProductRepository;

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
        $results = ProductGroupModel::query($args);

        return $results->map(function(ProductGroupModel $model) {
            return new ProductGroup([
                'products' => $model->products()
                    ->map(function($item) {
                        $item['product'] = ProductRepository::get($item['ec_product']);
                        return $item;
                    })
                    ->filter(function($item) {
                        return $item['product'];
                    })
                    ->map(function($item) use ($model) {
                        $product = $item['product'];
                        unset($item['product'], $item['ec_product']);

                        return $product->addMeta($item)->addMeta([
                            'description' => $model->meta->description,
                            'additional_description' => $model->meta->additional_description,
                            'icon' => $model->meta->icon,
                        ]);
                    })
                    ->map(function(Product $product) use ($model) {
                        $additionalProducts = $model->additionalProducts()
                            ->map(function($id) {
                                return ProductAdditionalRepository::get($id);
                            })->filter();

                        return $product->addAdditionalProducts($additionalProducts);
                    }),
            ]);
        });
    }
}
