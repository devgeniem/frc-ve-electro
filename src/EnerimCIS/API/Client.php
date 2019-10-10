<?php

namespace VE\Electro\EnerimCIS\API;

use Exception;
use VE\Electro\Support\Str;
use WP_Error;
use WP_Http_Curl;

class Client
{

    /**
     * @param $handle
     * @param $r
     * @param $url
     */
    public static function setCurlOptions($handle, $r, $url) {
        if (!env('ENERIM_KEY') && !env('ENERIM_CERT') || strpos($url, env('ENERIM_BASE_URL')) === false) {
            return;
        }

        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);

        $tempDir = trailingslashit(sys_get_temp_dir());

        // KEY
        $keyPath = $tempDir . 'key.pem';
        $keyResource = fopen($keyPath, 'w');
        $keyData = env('ENERIM_KEY');
        $keyData = str_replace('\n', "\n", $keyData);
        fwrite($keyResource, $keyData);

        curl_setopt($handle, CURLOPT_SSLKEY, $keyPath);

        //CERT
        $certPath = $tempDir . 'cert.pem';
        $certResource = fopen($certPath, 'w');
        $certData = env('ENERIM_CERT');
        $certData = str_replace('\n', "\n", $certData);
        fwrite($certResource, $certData);

        curl_setopt($handle, CURLOPT_SSLCERT, $certPath);

        if (env('QUOTAGUARDSTATIC_URL')) {
            $proxy_url = env('QUOTAGUARDSTATIC_URL');
            $proxy_url = parse_url($proxy_url);
            $proxy     = $proxy_url['host'] . ':' . $proxy_url['port'];
            $proxyauth = $proxy_url['user'] . ':' . $proxy_url['pass'];

            curl_setopt($handle, CURLOPT_PROXY, $proxy);
            curl_setopt($handle, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
            curl_setopt($handle, CURLOPT_PROXYUSERPWD, $proxyauth);
        }
    }

    /**
     *
     */
    public function cleanCertFiles() {
        $tempDir = trailingslashit(sys_get_temp_dir());
        $keyPath = $tempDir . 'key.pem';
        $certPath = $tempDir . 'cert.pem';
        unlink($keyPath);
        unlink($certPath);
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
        $baseUrl = env('ENERIM_BASE_URL');
        if (!$baseUrl) {
            throw new Exception('EnerimCIS API request failed. Env ENERIM_BASE_URL missing!');
        }
        $uri = trailingslashit($baseUrl) . $path;

        $args['method'] = Str::upper($method);

        $defaults = [
            'method'      => 'GET',
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

        add_action('http_api_curl', [__CLASS__, 'setCurlOptions'], 10, 3);
        $curl = new WP_Http_Curl();
        $response = $curl->request($uri, $args);
        remove_action('http_api_curl', [__CLASS__, 'setCurlOptions'], 10);

        $this->cleanCertFiles();

        if (is_wp_error($response)) {
            throw new Exception('EnerimCIS API request failed.');
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

    /**
     * @param array $args
     *
     * @return array|Response|WP_Error
     * @throws Exception
     */
    public function getProducts($args = []) {

        $urlPath = 'products/';
        $defaults = [
            'partyId'         => 'VE',
            'dynamicProperty' => 'ContractChannel_Verkkosivut'
        ];
        $args = wp_parse_args($args, $defaults);
        $path = add_query_arg($args, $urlPath);

        return $this->get($path);
    }

    /**
     * @return mixed
     */
    public function getProductsTest()
    {
        $uri = home_url('ContractChannel_Verkkosivut.json');
        return $this->get($uri);
    }
}
