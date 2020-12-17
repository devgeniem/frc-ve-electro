<?php

namespace VE\Electro\WordPress\Traits;

use VE\Electro\WordPress\Relations\Meta;

trait HasRelations
{
    protected $relations = [];

    protected function getRelationValue($key)
    {
        if (array_key_exists($key, $this->relations)) {
            return $this->relations[$key];
        }

        if (method_exists($this, $key)) {
            return $this->relations[$key] = $this->$key();
        }
    }
}
