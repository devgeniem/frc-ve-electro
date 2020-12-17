<?php

namespace VE\Electro\WordPress\Relations;

class Meta
{
    protected $attributes = [];

    protected $parent;

    public function __construct($parent)
    {
        $this->parent = $parent;
    }

    protected function getAttribute($key)
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        if (function_exists('get_field')) {
            $value = get_field($key, $this->parent->ID);
        } else {
            $value = get_post_meta($this->parent->ID, $key, true);
        }

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
}
