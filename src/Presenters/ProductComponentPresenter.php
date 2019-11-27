<?php

namespace VE\Electro\Presenters;

use VE\Electro\Electro;

class ProductComponentPresenter extends Presenter
{
    protected function description()
    {
        if ($this->entity->get('price_valid_duration') == 0) {
            return $this->entity->getDescription();
        }
        $unit = Electro::translate($this->entity->get('price_valid_duration_unit'));
        return $this->entity->get('price_valid_duration') . ' ' . $unit;
    }

    protected function price()
    {
        return $this->entity->price();
    }

}
