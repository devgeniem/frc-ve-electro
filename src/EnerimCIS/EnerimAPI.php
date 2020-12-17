<?php

namespace VE\Electro\EnerimCIS;

class EnerimAPI
{
    protected $client;

    protected $partyId;

    public function __construct($apiKey, $partyId, $options = [])
    {
        $this->client = new Http\Client($apiKey, $options);

        $this->partyId = $partyId;
    }

    public static function factory()
    {
        return new static(
            env('ENERIM_API_KEY'),
            env('ENERIM_API_PARTY_ID')
        );
    }

    public function products()
    {
        $endpoint = '/v3/products';

        $args = [
            'partyId'         => $this->partyId,
            'dynamicProperty' => 'ContractChannel_Verkkosivut'
        ];

        $endpoint = add_query_arg($args, $endpoint);

        return $this->client->get($endpoint);
    }

    public function product($id)
    {
        $endpoint = "/v3/products/{$id}";

        $args = [
            'partyId'         => $this->partyId,
            'dynamicProperty' => 'ContractChannel_Verkkosivut'
        ];

        $endpoint = add_query_arg($args, $endpoint);

        return $this->client->get($endpoint);
    }
}
