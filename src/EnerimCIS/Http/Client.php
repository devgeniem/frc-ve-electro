<?php

namespace VE\Electro\EnerimCIS\Http;

use Exception;
use VE\Electro\Support\Str;
use WP_Error;
use WP_Http_Curl;

class Client
{
    protected $baseUrl;

    protected $client;

    protected $middlewares = [];

    public function __construct($key, $cert, $proxyUrl)
    {
        $this->baseUrl = env('ENERIM_BASE_URL');

        $this->client = new WP_Http_Curl();

        $this->middlewares[] = new Middleware\Auth($key, $cert);

        if ($proxyUrl) {
            $this->middlewares[] = new Middleware\Proxy($proxyUrl);
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
        $baseUrl = $this->baseUrl;

        if (!$baseUrl) {
            throw new Exception('EnerimCIS API request failed. Env ENERIM_BASE_URL missing!');
        }
        $uri = trailingslashit($baseUrl) . trim($path, '/');

        $defaults = [
            'method'      => Str::upper($method),
            'timeout'     => 30,
            'redirection' => 10,
            'httpversion' => '1.1',
            'blocking' => true,
            'headers'     => [
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
                'User'         => 'Frantic',
                'User-Agent'   => ''
            ],
            'body'    => null,
            'cookies' => [],
            'stream' => false,
            'decompress' => true,
            'filename' => ''
        ];
        $args = wp_parse_args($args, $defaults);

        foreach($this->middlewares as $middleware) {
            add_action('http_api_curl', [$middleware, 'handle']);
        }

        $response = $this->client->request($uri, $args);

        foreach($this->middlewares as $middleware) {
            remove_action('http_api_curl', [$middleware, 'handle']);
        }

        if (is_wp_error($response)) {
            throw new Exception($response->get_error_message());
        }

        $response = new Response($response);

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
