<?php

namespace VE\Electro\Admin\Actions;

class EnerimSyncProducts
{
    public function register()
    {
        add_action('admin_post_enerim_sync_products', [$this, 'handle']);
    }

    public function handle()
    {
        check_admin_referer('enerim_sync_products');

        do_action('electro/products/sync');

        wp_safe_redirect(wp_get_referer());
    }
}
