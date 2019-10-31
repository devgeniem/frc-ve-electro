<?php

namespace VE\Electro\EnerimCIS\Http\Middleware;

use Exception;

class Auth
{
    protected $key;

    protected $cert;

    protected $tempFiles = [];

    public function __construct($key, $cert)
    {
        $this->key = $key;
        $this->cert = $cert;
    }

    public function handle($handle)
    {
        if (! $this->key || ! $this->cert) {
            throw new Exception('EnerimCIS API auth key or cert is missing.');
        }

        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);

        // Key
        $keyFile = $this->writeToFile('key.pem', $this->key);
        curl_setopt($handle, CURLOPT_SSLKEY, $keyFile);

        // Cert
        $certFile = $this->writeToFile('cert.pem', $this->cert);
        curl_setopt($handle, CURLOPT_SSLCERT, $certFile);
    }

    public function tearDown()
    {
        foreach($this->tempFiles as $file) {
            unlink($file);
        }
    }

    protected function writeToFile($file, $content)
    {
        $path = trailingslashit(sys_get_temp_dir()). $file;

        $resource = fopen($path, 'w');
        $content = str_replace('\n', "\n", $content);
        fwrite($resource, $content);

        $this->tempFiles[] = $path;

        return $path;
    }
}
