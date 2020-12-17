<?php

namespace VE\Electro\Support;

use Tightenco\Collect\Support\Collection;

class Support
{
    public function register()
    {
        $this->collectionMacros();
    }

    protected function collectionMacros()
    {
        /**
         * Recursive method for Collection
         */

        Collection::macro('recursive', function () {
            return $this->map(function ($value) {
                if (is_array($value) || is_object($value)) {
                    return static::make($value)->recursive();
                }

                return $value;
            });
        });
    }
}
