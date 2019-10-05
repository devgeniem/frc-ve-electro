<?php

namespace VE\Electro\Product\Collections;

use Carbon\Carbon;
use VE\Electro\EnerimCIS\Code;
use VE\Electro\Support\Collection as BaseCollection;

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
        if ( in_array($key, $this->dates) ) {
            return Carbon::create($value)
                ->settings([
                    'toStringFormat' => 'j.n.Y',
                ]);
        }

        // Mutate Enerim API codes automatically
        if ( is_string($value) || is_int($value) ) {
            return Code::get($value);
        }

        return $value;
    }
}
