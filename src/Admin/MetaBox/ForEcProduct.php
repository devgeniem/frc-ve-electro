<?php

namespace VE\Electro\Admin\MetaBox;

use VE\Electro\Models\Product;
use VE\Electro\Product\ProductRepository;

class ForEcProduct
{
    public function register()
    {
        add_action('add_meta_boxes', [$this, 'productPresenterData']);
        add_action('add_meta_boxes', [$this, 'productPayloadData']);
    }

    public function productPresenterData()
    {
        add_meta_box(
            'ec_product_present_data',
            'Formatted Data for UI',
            function($post) {
                $value = ProductRepository::get($post->ID)->present()->toArray();
                echo '<pre>'. json_encode($value, JSON_PRETTY_PRINT) . "</pre>";
            },
            Product::postType()
        );
    }

    public function productPayloadData()
    {
        add_meta_box(
            'ec_product_data',
            'EnerimcCIS API Payload',
            function($post) {
                $value = get_post_meta($post->ID, 'payload', true);
                echo '<pre>'. json_encode($value, JSON_PRETTY_PRINT) . "</pre>";
            },
            Product::postType()
        );
    }
}
