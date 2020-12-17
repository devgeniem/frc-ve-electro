<?php

namespace VE\Electro\Http\Rest;

use VE\Electro\Company\CompanyRepository;

class CompanyController
{
    protected $namespace = 'electro/v1';

    protected $endpoint = 'company';

    public function register()
    {
        add_action('rest_api_init', function() {
            register_rest_route(
                $this->namespace,
                "/{$this->endpoint}",
                [
                    'methods' => 'GET',
                    'callback' => [$this, 'handle'],
                ]
            );
        });
    }

    public function handle($request)
    {
        if ($postcode = $request['postcode']) {
            return (new CompanyRepository)->findByPostcode($postcode);
        }
    }
}
