<?php

namespace VE\Electro\Admin;

class Admin {

    public static function load() {
        add_action('add_meta_boxes', [__CLASS__, 'add_meta_boxes']);
        add_action('acf/init', [__CLASS__, 'add_settings_menu']);
        add_action('admin_menu', [__CLASS__, 'add_upload_page']);
        add_action('admin_post_enerim_json_upload', [__CLASS__, 'action_enerim_json_upload']);
    }

    public static function action_enerim_json_upload() {
        $data = [];

        // @TOOD: Sanitize and validate
        if (isset($_FILES['file'])) {
            $file = $_FILES['file']['tmp_name'];
            $data = file_get_contents($file);
            $data = json_decode($data, true);
        }

        do_action('electro/products/import', $data);

        wp_safe_redirect(wp_get_referer());
    }

    public static function add_settings_menu() {
        acf_add_options_page([
            'page_title'  => __('Settings'),
            'menu_title'  => __('Settings'),
            'menu_slug'   => 'enerimcis-settings',
            'parent_slug' => 'edit.php?post_type=ec_product',
        ]);
    }

    public static function add_upload_page() {
        add_submenu_page('edit.php?post_type=ec_product', __('Upload JSON'), __('Upload JSON'), 'manage_options', 'json-upload', function () {
            require 'views/upload.php';
        },);
    }

    public static function add_meta_boxes() {
        add_meta_box('ec_product_card', 'Product Card', function ($post) {
            require 'views/card.php';
        }, 'ec_product');

        add_meta_box('ec_product_payload', 'Flatted Payload', function ($post) {
            require 'views/payload.php';
        }, 'ec_product',);

        add_meta_box('ec_product_data', 'API Payload', function ($post) {
            $value = get_post_meta($post->ID, 'payload', true);
            echo '<pre>' . json_encode($value, JSON_PRETTY_PRINT) . "</pre>";
        }, 'ec_product',);

    }
}
