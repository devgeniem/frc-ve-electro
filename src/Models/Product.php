<?php

namespace VE\Electro\Models;

use VE\Electro\WordPress\Model;
use VE\Electro\Product\PayloadCollection;

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

    protected $payloadMutated;

    /**
     * Attribute mutator getters
     */

    protected function getPayloadAttribute($value)
    {
        if ($this->payloadMutated) {
            return $this->payloadMutated;
        }

        return $this->payloadMutated = collect($this->meta->payload);
    }
}
