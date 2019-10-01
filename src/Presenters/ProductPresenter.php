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

    public function contract_duration()
    {
        return 'Sopimuksen kesto: ' . $this->entity->getContractDuration();
    }

    public function price_period()
    {
        $main = $this->entity->components()->first();
        $from = $main->get('valid_from')->setTimezone('Europe/Helsinki')->format('j.n.');
        $to = $main->get('valid_to')->setTimezone('Europe/Helsinki')->subDay()->format('j.n.Y');
        return "Hintajakso: {$from}-{$to}";
    }

    public function meta()
    {
        $keys = $this->entity->getMeta();
        return array_map(function($key) {
            return $this->$key;
        }, $keys);
    }

    public function order_link()
    {
        return $this->entity->getOrderLink();
    }

    public function isType($type) {
        return $this->entity->isType($type);
    }
}
