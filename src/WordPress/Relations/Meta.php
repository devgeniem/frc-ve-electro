<?php

namespace VE\Electro\WordPress\Relations;

use IteratorAggregate;
use ArrayIterator;
use VE\Electro\Support\Str;

class Meta implements IteratorAggregate
{
    protected $attributes = [];

    protected $parent;

    public function __construct($parent)
    {
        $this->parent = $parent;
    }

    public function toArray()
    {
        return $this->attributes;
    }

    public function all()
    {
        return get_post_meta($this->parent->ID);
    }

    protected function getAttribute($key)
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        $value = get_post_meta($this->parent->ID, $key, true);

        return $this->attributes[$key] = $value;
    }

    protected function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    public function getIterator() {
        return new ArrayIterator($this->attributes);
    }
}
