<?php

namespace VE\Electro\Actions;

use VE\Electro\EnerimCIS\EnerimAPI;
use VE\Electro\Product\ProductRepository;

class Products
{
    public $action = 'electro/products';

    protected $api;

    protected $repository;

    public function __construct()
    {
        $this->api = EnerimAPI::factory();
        $this->repository = new ProductRepository;
    }

    public function delete($ids = [])
    {
        $this->repository->delete($ids);

        do_action('electro/notice/success', 'Deleted products from database.');
    }

    public function sync()
    {
        try {
            $response = $this->api->products()->toArray();
        } catch(\Exception $e) {
            do_action('electro/log', $e->getMessage());
            do_action('electro/notice/error', 'EnerimCIS API request failed.');
            return;
        }

        do_action('electro/products/save', $response);
        do_action('electro/products/purge', $response);

        do_action('electro/notice/success', 'Synchronized products from Enerim.');
    }

    public function import($ids = [])
    {
        try {
            $response = array_map(function($id) {
                return $this->api->product($id)->toArray();
            }, $ids);
        } catch(\Exception $e) {
            do_action('electro/log', $e->getMessage());
            do_action('electro/notice/error', 'EnerimCIS API request failed.');
            return;
        }

        do_action('electro/products/save', $response);

        do_action('electro/notice/success', 'Imported product(s) from Enerim.');
    }

    public function save($response)
    {
        $imported = $this->repository->import($response);

        if (! $imported) {
            return do_action('electro/notice/success', 'Nothing to import.');
        }

        do_action(
            'electro/notice/info',
            sprintf('Imported or updated product(s): %s.', join(', ', $imported))
        );
    }

    public function purge($response)
    {
        $purged = $this->repository->purge($response);

        if (! $purged) {
            return do_action('electro/notice/info', 'Nothing to purge.');
        }

        do_action(
            'electro/notice/info',
            sprintf('Purged product(s): %s.', join(', ', $purged))
        );
    }
}
