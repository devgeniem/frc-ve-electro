<?php

namespace VE\Electro\Admin\Acf;

abstract class Field
{
    protected function field($key)
    {
        return $this->key('field', $key);
    }

    protected function group($key)
    {
        return $this->key('group', $key);
    }

    protected function key($prefix, $value)
    {
        $id = static::class;
        $prefix = "{$prefix}_";
        $value = md5("ve_electro_{$id}_{$value}");

        return sprintf('%s%s', $prefix, $value);
    }
}
