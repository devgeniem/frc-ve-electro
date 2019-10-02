<?php

namespace VE\Electro\Models;

use VE\Electro\WordPress\Model;
use VE\Electro\Product\ProductRepository;

class ProductGroup extends Model
{
    protected $post_type = 'product_group';
    protected $show_ui = true;
    protected $supports = [
        'title',
    ];

    protected $labels = [
        'name' => 'Product Groups',
    ];

    protected function getProductsAttribute()
    {
        $ids = collect($this->meta->ec_products);
        return $ids->map(function($id) {
            return ProductRepository::get($id);
        });
    }
}
