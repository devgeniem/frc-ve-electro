<?php

namespace VE\Electro\Presenters;

use VE\Electro\Support\Str;
use VE\Electro\Electro;

class ProductPresenter extends Presenter
{
    public function title()
    {
        $title = $this->entity->getMeasurementMethodName();
        return Str::ucfirst($title);
    }

    public function subtitle()
    {
        return $this->name;
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
        return Electro::translate('contract_duration') . ': ' . $this->entity->getContractDuration();
    }

    public function price_period()
    {
        $main = $this->entity->components()->first();
        if (! $main) {
            return;
        }
        $from = $main->get('valid_from')->setTimezone('Europe/Helsinki')->format('j.n.');
        $to = $main->get('valid_to')->setTimezone('Europe/Helsinki')->subDay()->format('j.n.Y');
        return Electro::translate('price_period') . ": {$from}-{$to}";
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

    public function order_text()
    {
        return 'Tilaa';
    }

    public function isType($type) {
        return $this->entity->isType($type);
    }

    public function hasComponents()
    {
        return (bool) $this->entity->components();
    }
}
