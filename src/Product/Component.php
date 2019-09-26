<?php

namespace VE\Electro\Product;

use Carbon\Carbon;

use VE\Electro\Electro;

class Component extends PayloadCollection
{
    public function getName()
    {
        return $this->get('component_name');
    }

    public function getDescription()
    {
        $items = collect($this->items)->recursive();

        return $items->get('product_component_descriptions')
            ->where('language', Electro::langId()) // @TODO
            ->first()
            ->get('description');
    }

    public function getMeteringUnit()
    {
        $value = $this->get('tariff_id');

        // @TODO
        if ( $value === 144002 ) {
            return Electro::translate('month');
        }

        return 'kWh';
    }

    public function price(array $args = [])
    {
        $defaults = [
            'amount' => $this->get('unit_price'),
            'currency' => $this->get('currency'),
            'vat' => $this->get('vat_rate'),
            'withVat' => true,
            'decimals' => $this->get('price_decimals'),
            'perUnit' => $this->getMeteringUnit(),
        ];

        $args = wp_parse_args($args, $defaults);

        return new Price($args);
    }


    public function isCurrent()
    {
        $now = Carbon::now();
        $from = $this->get('valid_from');
        $to = $this->get('valid_to');
        return $now->greaterThanOrEqualTo($from) && $now->lessThan($to);
    }

    public function isActive()
    {
        $now = Carbon::now();
        $from = $this->get('valid_from')->subDays(14);
        $to = $this->get('valid_to');
        return $now->greaterThanOrEqualTo($from) && $now->lessThan($to);
    }

    public function isPrev()
    {
        $now = Carbon::now();
        $to = $this->get('valid_to');
        return $now->greaterThanOrEqualTo($to);
    }

    public function isNext()
    {
        $now = Carbon::now();
        $from = $this->get('valid_from');
        return $from->greaterThanOrEqualTo($now);
    }

}
