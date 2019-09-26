<?php

namespace VE\Electro\Actions;

use VE\Electro\EnerimCIS;
use VE\Electro\Models\Product;

 // @TODO: Add logger

class Products extends Action
{
    public function delete($ids = [])
    {
        if ( $ids ) {
            $products = Product::query([
                'post_name__in' => $ids,
            ]);
        }

        if ( !$ids ) {
            $products = Product::all();
        }

        foreach($products as $product) {
            $product->delete();
        }
    }

    public function sync($ids = [])
    {
        // @TODO: Separate sync to import and use sync method
        // to run import and purge methods with same payload

        $client = new EnerimCIS\API\Client();
        $response = $client->getProducts()->getBody();

        foreach($response as $payload) {
            $id = Product::updateOrCreate([
                'post_title' => $payload['product_name'],
                'post_status' => 'publish',
                'meta_input' => [
                    'payload' => $payload,
                ],
            ]);
        }

        // @TODO: Add support for single product imports
    }

    public function purge()
    {
        $client = new EnerimCIS\API\Client();
        $response = $client->getProducts()->getBody();

        $productsFromAPI = collect($response)
            ->pluck('product_name')
            ->toArray();

        $productsFromDB = Product::all();

        $missing = $productsFromDB
            ->map(function($product) {
                return $product->post_title;
            })
            ->diff($productsFromAPI)
            ->toArray();

        $productsToDelete = $productsFromDB
            ->filter(function($product) use($missing) {
                return in_array($product->post_title, $missing);
            });

        foreach($productsToDelete as $product) {
            $product->delete();
        }
    }
}
