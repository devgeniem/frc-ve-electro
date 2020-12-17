<?php

namespace VE\Electro\CLI\Commands;

class Products extends Command
{
    protected $command = 'electro products';

    /**
     * Sync (import + purge) all products from EneriemCIS API
     */
    public function sync()
    {
        do_action('electro/log', 'Starting sync.');

        do_action('electro/products/sync');

        do_action('electro/log', 'Finished sync.');
    }

    /**
     * Import products from EneriemCIS API
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
     * @alias update
     */
    public function import($ids, $assoc_args)
    {
        do_action('electro/log', 'Starting import.');

        if (! empty($assoc_args['id'])) {
            $ids = explode(',', $assoc_args['id']);
        }

        do_action('electro/products/import', $ids);

        do_action('electro/log', 'Finished import.');
    }

    /**
     * Delete all or defined products from WordPress database
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
     * @alias update
     */
    public function delete($ids, $assoc_args)
    {
        do_action('electro/log', 'Starting delete.');

        if (! empty($assoc_args['id'])) {
            $ids = explode(',', $assoc_args['id']);
        }

        do_action('electro/products/delete', $ids);

        do_action('electro/log', 'Finished delete.');
    }
}
