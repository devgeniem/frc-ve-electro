<?php

namespace VE\Electro\Models;

use VE\Electro\WordPress\Model;

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

    protected $translatable = true;

    public function products()
    {
        return collect($this->meta->ec_products)
            ->where('ec_product')
            ->filter();
    }

    public function additionalProducts()
    {
        return collect($this->meta->additional_products)
            ->filter();
    }
}
