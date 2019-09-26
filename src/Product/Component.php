<?php

namespace VE\Electro\Product;

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
}
