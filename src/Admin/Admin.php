<?php

namespace VE\Electro\Admin;

class Admin
{
    public static function load()
    {
        add_action('add_meta_boxes', [__CLASS__, 'add_meta_boxes']);
    }

    public static function add_meta_boxes()
    {
        add_meta_box(
            'ec_product_card',
            'Product Card',
            function($post) {
                require 'views/card.php';
            },
            'ec_product'
        );

        add_meta_box(
            'ec_product_data',
            'API Payload',
            function($post) {
                $value = get_post_meta($post->ID, 'payload', true);
                echo '<pre>'. json_encode($value, JSON_PRETTY_PRINT) . "</pre>";
            },
            'ec_product',
        );

    }
}
