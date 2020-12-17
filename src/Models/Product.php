<?php

namespace VE\Electro\Models;

use VE\Electro\WordPress\Model;

class Product extends Model
{
    protected $post_type = 'ec_product';
    protected $show_ui = true;
    protected $supports = [
        'title',
    ];

    protected $labels = [
        'name' => 'Enerim Products',
    ];

    public function payload()
    {
        return collect($this->meta->payload)->recursive();
    }
}
