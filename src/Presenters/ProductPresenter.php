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

    public function measurementMethodId() {
        return $this->entity->getMeasurementMethodId();
    }

    // @todo: change this to subtitle
    public function subtitle()
    {
        $subtitle = $this->entity->getSubtitle();
        $code = ' (' . $this->name . ')';
        $title = $subtitle.$code;
        if ($this->isCompany()) {
            $title = $code;
        }
        return $title;
    }

    public function name()
    {
        return $this->entity->getProductName();
    }

    public function description() {
        return $this->entity->getDescription();
    }

    // @todo: hack
    public function getPacketSubtitle() {
        if (strpos($this->description(), ' S') !== false ) {
            return '<2000 kWh/v' . ' (' . $this->name . ')';
        } else if (strpos($this->description(), ' M') !== false ) {
            return '2000-3000 kWh/v' . ' (' . $this->name . ')';
        }else if (strpos($this->description(), ' L') !== false ) {
            return '3000-4000 kWh/v' . ' (' . $this->name . ')';
        }
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

    public function price_period_from()
    {
        $main = $this->entity->components()->first();
        if (! $main) {
            return;
        }
        $from = $main->get('valid_from')->setTimezone('Europe/Helsinki')->format('j.n.Y');
        return Electro::translate('price_period') . ": " . sprintf(Electro::translate('from'), $from);
    }

    public function meta()
    {
        $keys = $this->entity->getMeta();
        return array_map(function($key) {
            return $this->$key;
        }, $keys);
    }

    public function isOrderable()
    {
        return $this->entity->isActive();
    }

    public function order_link()
    {
        return $this->entity->getOrderLink();
    }

    public function order_text()
    {
        return Electro::translate('order');
    }

    public function isType($type) {
        return $this->entity->isType($type);
    }

    public function hasComponents()
    {
        return (bool) $this->entity->components();
    }

    public function getCustomerType() {
        return $this->entity->getCustomerType();
    }

    public function isCompany() {
        return $this->entity->isCompany();
    }

    public function getType() {
        return $this->entity->getType();
    }
}
