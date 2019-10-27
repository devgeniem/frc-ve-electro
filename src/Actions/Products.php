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

        do_action(
            'electro/notice/success',
            'Deleted products from database.'
        );
    }

    public function sync()
    {
        try {
            $response = $this->api->products()->toArray();

            if (empty($response)) {
                return do_action(
                    'electro/notice/success',
                    'Nothing to import.'
                );
            }

            do_action('electro/products/upload', $response);

            $this->repository->purge($response);

            do_action(
                'electro/notice/success',
                'Synchronized products from Enerim.'
            );
        } catch(\Exception $e) {
            do_action('electro/log', $e->getMessage());
            do_action('electro/notice/error', 'EnerimCIS API request failed.');
        }
        
    }

    public function import($ids = [])
    {
        $response = array_map(function($id) {
            return $this->api->product($id)->toArray();
        }, $ids);
    
        if (! $response) {
            return do_action(
                'electro/notice/success',
                'Nothing to import.'
            );
        }

        do_action('electro/products/upload', $response);

        do_action(
            'electro/notice/success',
            'Imported products from Enerim.'
        );

    }

    public function upload($response)
    {
        $this->repository->import($response);

        $ids = wp_list_pluck($response, 'product_name');

        do_action(
            'electro/notice/info', 
            sprintf('Imported products: %s.', join(', ', $ids))
        );

    }
}
