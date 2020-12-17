<?php

namespace VE\Electro\EnerimCIS\Http;

use Exception;
use VE\Electro\Support\Str;
use WP_Error;
use WP_Http_Curl;

class Client
{
    protected $baseUrl;

    protected $apiKey;

    protected $http;

    protected $middlewares = [];

    public function __construct($apiKey, $options = [])
    {
        $this->baseUrl = env('ENERIM_API_BASE_URL');

        $this->apiKey = $apiKey;

        $this->http = new WP_Http_Curl();

        if (isset($options['middlewares']) && is_array($options['middlewares'])) {
            $this->middlewares = $options['middlewares'];
        }
    }

    /**
     * @param $method
     * @param $path
     * @param array $args
     *
     * @return array|Response|WP_Error
     * @throws Exception
     */
    public function request($method, $path, $args = [])
    {
        if (! $this->baseUrl) {
            throw new Exception('EnerimCIS API request failed. Env ENERIM_API_BASE_URL missing!');
        }

        if (! $this->apiKey) {
            throw new Exception('EnerimCIS API request failed. Env ENERIM_API_KEY missing!');
        }

        $uri = trailingslashit($this->baseUrl) . trim($path, '/');

        $defaults = [
            'method'      => Str::upper($method),
            'timeout'     => 30,
            'redirection' => 10,
            'httpversion' => '1.1',
            'blocking' => true,
            'headers'     => [
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
                'User-Agent'   => '',
                'X-ApiKey'     => $this->apiKey,
            ],
            'body'    => null,
            'cookies' => [],
            'stream' => false,
            'decompress' => true,
            'filename' => '',
        ];

        $args = wp_parse_args($args, $defaults);

        foreach($this->middlewares as $middleware) {
            add_action('http_api_curl', [$middleware, 'handle']);
        }

        $response = $this->http->request($uri, $args);

        foreach($this->middlewares as $middleware) {
            remove_action('http_api_curl', [$middleware, 'handle']);
            if (method_exists($middleware, 'tearDown')) {
                $middleware->tearDown();
            }
        }

        if (is_wp_error($response)) {
            throw new Exception($response->get_error_message());
        }

        $response = new Response($response);

        if ($response->getStatusCode() !== 200) {
            $data = $response->toArray();
            throw new Exception(
                sprintf('EnerimCIS API request failed (error %s) with response: %s', $response->getStatusCode(), json_encode($data))
            );
        }

        if (! Str::contains($response->getHeader('content-type'), 'application/json')) {
            throw new Exception('EnerimCIS API request returned a non-JSON result.');
        }

        if (! $response->isValid()) {
            throw new Exception('EnerimCIS API request is not valid.');
        }

        return $response;
    }

    /**
     * @param $name
     * @param $args
     *
     * @return array|Response|WP_Error
     * @throws Exception
     */
    public function __call($name, $args)
    {
        /**
         * Allow direct GET requests
         */
        $method = Str::upper($name);

        if (! in_array($method, ['GET'])) {
            throw new Exception(sprintf('EnerimCIS API: Method \'%s\' is not supported.', $method));
        }

        return $this->request($method, ...$args);
    }
}
