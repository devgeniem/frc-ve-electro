<?php

namespace VE\Electro\Presenters;

use VE\Electro\Support\Str;

class ProductPresenter extends Presenter
{
    public function title()
    {
        $title = $this->entity->getMeasurementMethodName();
        return Str::ucfirst($title);
    }

    public function name()
    {
        return $this->entity->getProductName();
    }

    public function components()
    {
        return (object) [
            'primary' => $this->entity->components()->type('primary'),
            'secondary' => $this->entity->components()->type('secondary'),
        ];
    }
}
