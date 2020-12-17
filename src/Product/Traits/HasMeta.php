<?php

namespace VE\Electro\Product\Traits;

trait HasMeta
{
    protected $meta = [];

    public function addMeta($meta = [])
    {
        $this->meta = array_merge($meta, $this->meta);

        return $this;
    }

    public function withMeta($meta)
    {
        return $this->addMeta($meta);
    }

    public function meta($key = null)
    {
        if (! $key) {
            return $this->meta;
        }

        if ($key && array_key_exists($key, $this->meta)) {
            return ! empty($this->meta[$key])
                ? $this->meta[$key]
                : null;
        }
    }
}
