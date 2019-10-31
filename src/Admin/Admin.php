<?php

namespace VE\Electro\Admin;

class Admin
{
    public function registerHooks()
    {
        add_action('acf/init', [$this, 'acf_init']);
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('admin_menu', [$this, 'add_upload_page']);
        add_action('admin_menu', [$this, 'add_sopa_page']);

        add_action(
            'manage_ec_product_posts_custom_column',
            [new ListTable\DisplayLastModified, 'handle'], 10, 2
        );

        add_action(
            'manage_ec_product_posts_columns',
            [new ListTable\AddLastModified, 'handle']
        );

        add_action(
            'admin_bar_menu',
            [new AdminBar\SyncEnerimProducts, 'handle'], 999
        );

        add_action(
            'admin_post_enerim_sync_products',
            [new Actions\EnerimSyncProducts, 'handle']
        );

        add_action(
            'admin_post_enerim_json_upload',
            [new Actions\EnerimJsonUpload, 'handle']
        );

        add_action(
            'admin_notices',
            [new Notices\Notices, 'handle']
        );
    }

    public function add_upload_page()
    {
        add_submenu_page(
            'edit.php?post_type=ec_product',
            __('Upload JSON'),
            __('Upload JSON'),
            'manage_options',
            'json-upload',
            function() {
                require 'views/upload.php';
            }
        );
    }

    public function add_sopa_page()
    {
        add_submenu_page(
            'edit.php?post_type=ec_product',
            __('SOPA link generator'),
            __('SOPA link generator'),
            'manage_options',
            'sopa-link',
            function() {
                require 'views/sopa.php';
            }
        );
    }

    public function acf_init()
    {
        $fields = [
            Acf\ProductGroupFields::class,
        ];

        foreach($fields as $field) {
            (new $field)->register();
        }
    }

    public function add_meta_boxes()
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
            'ec_product_payload',
            'Flatted Payload',
            function($post) {
                require 'views/payload.php';
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
            'ec_product'
        );

    }
}
