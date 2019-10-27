<?php

namespace VE\Electro\EnerimCIS;

class EnerimAPI
{
    protected $client;

    public function __construct($key, $cert, $proxy)
    {
        $this->client = new API\Client($key, $cert, $proxy);
    }

    public static function factory()
    {
        return new static(
            env('ENERIM_KEY'),
            env('ENERIM_CERT'),
            env('QUOTAGUARDSTATIC_URL')
        );
    }

    public function products($ids = [])
    {
        $endpoint = '/products';

        $args = [
            'partyId'         => 'VE',
            'dynamicProperty' => 'ContractChannel_Verkkosivut'
        ];

        $endpoint = add_query_arg($args, $endpoint);

        return $this->client->get($endpoint);
    }

    public function product($id)
    {
        $endpoint = "/products/{$id}";

        $args = [
            'partyId'         => 'VE',
            'dynamicProperty' => 'ContractChannel_Verkkosivut'
        ];

        $endpoint = add_query_arg($args, $endpoint);

        return $this->client->get($endpoint);
    }
}