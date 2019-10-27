<?php

namespace VE\Electro\EnerimCIS\API\Middlewares;

class Auth
{
    protected $key;
    
    protected $cert;

    public function __construct($key, $cert) 
    {
        $this->key = $key;
        $this->cert = $cert;
    }

    public function handle($handle)
    {
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);

        // Key
        $keyFile = $this->writeToFile('key.pem', $this->key);
        curl_setopt($handle, CURLOPT_SSLKEY, $keyFile);

        // Cert
        $certFile = $this->writeToFile('cert.pem', $this->cert);
        curl_setopt($handle, CURLOPT_SSLCERT, $certFile);
    }

    protected function writeToFile($file, $content)
    {
        $path = trailingslashit(sys_get_temp_dir()). $file;

        if (file_exists($path)) {
            return $path;
        }

        $resource = fopen($path, 'w');
        $content = str_replace('\n', "\n", $content);
        fwrite($resource, $content);

        return $path;
    }
}