<?php

namespace VE\Electro\Company;

class CompanyRepository
{
    protected static $companies = [
        'OOMI' => [
            'id' => 'OOMI',
            'redirect' => true,
            'sopa' => 'https://online.oomi.fi/NewContract/Contract/EndProcess',
            'title' => 'Oomin',
        ],
        'OSS000' => [
            'id' => 'OSS',
            'redirect' => false,
            'sopa' => null,
            'web' => [
                'B2B' => 'https://www.oulunseudunsahko.fi/sahko/sahkon-myynti/tee-sahkosopimus-yritykselle.html',
                'B2C' => 'https://www.oulunseudunsahko.fi/sahko/sahkon-myynti/tee-sahkosopimus-kuluttaja.html'
            ],
            'title' => 'Oulun Seudun Sähkön',
        ],
        'LEO000' => [
            'id' => 'LEO',
            'redirect' => false,
            'sopa' => 'https://online.lahtienergia.fi/NewContract/Contract',
            'title' => 'Lahti Energian',
        ],
        'OE0000' => [
            'id' => 'OE',
            'redirect' => false,
            'sopa' => 'https://www.energiatili.fi/NewContract/Contract',
            'title' => 'Oulun Energian',
        ],
    ];

    public function findById($id)
    {
        if (array_key_exists($id, $companies = static::$companies)) {
            return $companies[$id];
        }
    }

    public function findByPostcode($code)
    {
        return $this->findById(
            $this->readFromCsv($code)
        );
    }

    protected function readFromCsv($code)
    {
        if (strlen($code) !== 5) {
            return $this->defaultCompany();
        }

        $path = realpath(__DIR__ . '/../../resources/companies.csv');

        if (! file_exists($path)) {
            return $this->defaultCompany();
        }

        $source = fopen($path, 'r');

        while (($data = fgetcsv($source, 512, ';')) !== false) {
            if (isset($data[2]) && $data[2] === $code) {
                fclose($source);
                return $data[0];
            }
        }

        return $this->defaultCompany();
    }

    public function defaultCompany()
    {
        return 'OOMI';
    }
}
