<?php

namespace VE\Electro\Presenters\Traits;

trait HasPresenter
{
    public function present()
    {
        if ($this->presenter && class_exists($this->presenter)) {
            return new $this->presenter($this);
        }
    }
}
