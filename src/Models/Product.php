<?php

namespace VE\Electro\Models;

use VE\Electro\WordPress\Model;
use VE\Electro\Product\PayloadCollection;
use VE\Electro\Product\ComponentCollection;
use VE\Electro\Product\Component;

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
    protected $componentsMutated;

    /**
     * Attribute mutator getters
     */

    protected function getPayloadAttribute($value)
    {
        if ($this->payloadMutated) {
            return $this->payloadMutated;
        }

        return $this->payloadMutated = collect($this->meta->payload)->recursive();
    }

    protected function getComponentsAttribute($value)
    {
        if ($this->componentsMutated) {
            return $this->componentsMutated;
        }

        $prices = $this->payload
            ->get('product_components', collect([]))
            ->map(function($component) {
                // Merge component_prices and single product_components item
                // to get flat data model for product_components
                $merged = $component
                    ->get('component_prices')
                    ->map(function($price) use($component) {
                        return $price
                            ->merge($component)
                            ->forget('component_prices');
                    });

                // Replace component_prices with merged data
                $component->put('component_prices', $merged);

                return $component;
            })
            ->pluck('component_prices')
            ->collapse()
            ->sortBy('sort_order');

        // $prices = $this->mutateComponents($prices);

        // Map component_prices items to be Component objects
        return $this->componentsMutated =
            (new ComponentCollection($prices))->mapInto(Component::class);
    }
}
