<?php

namespace VE\Electro\EnerimCIS\Http\Middleware;

class Proxy
{
    protected $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function handle($handle)
    {
        $proxy_url = parse_url($this->url);
        $proxy     = $proxy_url['host'] . ':' . $proxy_url['port'];
        $proxyauth = $proxy_url['user'] . ':' . $proxy_url['pass'];

        curl_setopt($handle, CURLOPT_PROXY, $proxy);
        curl_setopt($handle, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
        curl_setopt($handle, CURLOPT_PROXYUSERPWD, $proxyauth);
    }
}
