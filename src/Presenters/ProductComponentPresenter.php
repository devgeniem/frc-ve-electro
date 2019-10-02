<?php

namespace VE\Electro\Presenters;

class ProductComponentPresenter extends Presenter
{
    protected function description()
    {
        return $this->entity->getDescription();
    }

    protected function price()
    {
        return $this->entity->price();
    }

}
