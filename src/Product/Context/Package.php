<?php

namespace VE\Electro\Product\Context;

use Carbon\Carbon;

class Package extends Standard
{
    public const TYPE = 'package';

    public function components($components)
    {
        return $components->filter(function($item) {
            return $item['sort_order'] != 2;
        })->map(function($item) {
            $item['sort_order'] = 2;
            return $item;
        });
    }
}
