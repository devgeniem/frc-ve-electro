<?php

namespace VE\Electro\EnerimCIS\API;

class Response
{
    protected $response;

    public function __construct($response)
    {
        $this->response = $response;
    }

    public function getBody()
    {
        return json_decode(wp_remote_retrieve_body($this->response), true);
    }

    public function getStatusCode()
    {
        return wp_remote_retrieve_response_code($this->response);
    }

    public function getHeaders()
    {
        return wp_remote_retrieve_headers($this->response);
    }

    public function getHeader($key)
    {
        $headers = $this->getHeaders();
        return $headers[$key] ?? null;
    }

    public function isValid() {
        return is_array($this->getBody());
    }
}
