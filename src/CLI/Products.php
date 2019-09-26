<?php

namespace VE\Electro\CLI;

use WP_CLI;

class Products
{

    /**
     * Sync products from EneriemCIS API
     *
     * ## OPTIONS
     *
     * [<ID>...]
     * : IDs of the products.
     *
     * [--id=<string>]
     * : IDs of the products
     *
     * ## EXAMPLE
     *
     * $ wp electro products sync ROS24PE1 ROS24PE2
     *
     * or
     *
     * $ wp electro products sync --id=ROS24PE1,ROS24PE2
     *
     */

    public function sync($ids, $assoc_args)
    {
        if (! empty($assoc_args['id'])) {
            $ids = explode(',', $assoc_args['id']);
        }

        do_action('electro/products/sync', $ids);
    }

    public function delete($ids, $assoc_args)
    {
        if (! empty($assoc_args['id'])) {
            $ids = explode(',', $assoc_args['id']);
        }

        do_action('electro/products/delete', $ids);
    }

    /**
     *
     * Purge products not in EneriemCIS API from WordPress
     *
     * ## EXAMPLES
     *
     * $ wp electro products purge
     *
     */
    public function purge()
    {

        do_action('electro/products/purge');
    }
}
