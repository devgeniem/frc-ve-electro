<?php

namespace VE\Electro\Product\Collections;

use Carbon\CarbonImmutable;
use VE\Electro\EnerimCIS\Enums\Code;
use Tightenco\Collect\Support\Collection as BaseCollection;

class PayloadCollection extends BaseCollection
{
    protected $dates = [
        'valid_from',
        'valid_to',
    ];

    public function get($key, $default = null)
    {
        $value = parent::get($key, $default);

        // Cast dates to Carbon object
        if ($value && in_array($key, $this->dates) ) {
            return CarbonImmutable::parse($value);
        }

        // Mutate Enerim API codes automatically
        if ( is_string($value) || is_int($value) ) {
            return Code::get($value);
        }

        return $value;
    }
}
