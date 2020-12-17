<?php

namespace VE\Electro\Admin\AdminMenu;

class SopaLinkGenerator
{
    public function register()
    {
        add_action('admin_menu', [$this, 'handle']);
    }

    public function handle()
    {
        add_submenu_page(
            'edit.php?post_type=ec_product',
            __('SOPA link generator', 'electro'),
            __('SOPA link generator', 'electro'),
            'manage_options',
            'sopa-link',
            function() {
                require realpath(__DIR__ . '/../views/sopa.php');
            }
        );
    }
}
