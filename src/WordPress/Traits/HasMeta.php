<?php

namespace VE\Electro\WordPress\Traits;

use VE\Electro\WordPress\Relations\Meta;

trait HasMeta
{
    protected function meta()
    {
        return new Meta($this);
    }
}
