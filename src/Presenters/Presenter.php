<?php

namespace VE\Electro\Presenters;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use VE\Electro\Electro;

abstract class Presenter implements IteratorAggregate, ArrayAccess
{
    protected $items;

    abstract public function data(): array;

    protected function language()
    {
        return Electro::langId();
    }

    protected function translate($key)
    {
        return Electro::translate($key);
    }

    public function toArray()
    {
        return $this->items ?? $this->items = $this->data();
    }

    public function offsetExists($key)
    {
        return array_key_exists($key, $this->toArray());
    }

    public function offsetGet($key)
    {
        if ($this->offsetExists($key)) {
            return $this->toArray()[$key];
        }
    }

    public function offsetSet($key, $value)
    {
    }

    public function offsetUnset($key)
    {
    }

    public function getIterator()
    {
        return new ArrayIterator($this->toArray());
    }
}
