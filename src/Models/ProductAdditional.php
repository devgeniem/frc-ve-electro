<?php

namespace VE\Electro\Models;

use VE\Electro\WordPress\Model;

class ProductAdditional extends Model
{
    protected $post_type = 'product_additional';
    protected $show_ui = true;
    protected $supports = [
        'title',
    ];

    protected $labels = [
        'name' => 'Additional Products',
    ];

    protected $translatable = true;

    public function products()
    {
        return collect($this->meta->ec_products)
            ->where('ec_product')
            ->filter();
    }
}
