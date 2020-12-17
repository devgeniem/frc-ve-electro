<?php

namespace VE\Electro\Admin\AdminBar;

class SyncEnerimProducts
{
    public function register()
    {
        add_action('admin_bar_menu', [$this, 'handle'], 999);
    }

    public function handle($bar)
    {
        if (! is_admin()) {
            return;
        }

        $screen = get_current_screen();

        if (! $screen || $screen->post_type != 'ec_product') {
            return;
        }

        $args = [
            'id'    => 've_electro_enerim_sync_products',
            'title' => '<span class="ab-icon"></span><span class="ab-label">'.__('Fetch from EnerimCIS', 'electro').'</span>',
            'href'  => wp_nonce_url(
                add_query_arg([
                    'action' => 'enerim_sync_products',
                ], admin_url('admin-post.php')),
                'enerim_sync_products'
            ),
        ];

        $bar->add_node($args);
    }
}
