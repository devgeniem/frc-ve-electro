<?php

namespace VE\Electro\Admin\AdminMenu;

class UploadJson
{
    public function register()
    {
        add_action('admin_menu', [$this, 'handle']);
    }

    public function handle()
    {
        add_submenu_page(
            'edit.php?post_type=ec_product',
            __('Upload JSON', 'electro'),
            __('Upload JSON', 'electro'),
            'manage_options',
            'json-upload',
            function() {
                require realpath(__DIR__ . '/../views/upload.php');
            }
        );
    }
}
