<?php

namespace VE\Electro\Presenters;

class ProductPresenter extends Presenter
{
    public function title()
    {
        return $this->entity->getMeasurementMethodName();
    }

    public function components()
    {
        return (object) [
            'primary' => $this->entity->components,
            'secondary' => $this->entity->components,
        ];
    }
}
