<?php

namespace VE\Electro\Product\Variations;

use VE\Electro\Electro;
use VE\Electro\Product\Component;
use VE\Electro\Product\PayloadCollection;
use VE\Electro\Product\ComponentCollection;

class Standard
{
    protected $payload;
    public $components;

    protected $componentsMutated;
    protected $filters = [];

    public function __construct($model)
    {
        $this->payload = (new PayloadCollection($model->payload))->recursive();
        $this->components = $model->components;
    }

    public function filter(array $args = [])
    {
        $this->filters = $args;
        return $this;
    }

    public function getType()
    {
        return 'standard';
    }

    public function isType($key)
    {
        return $this->getType() == $key;
    }

    public function periods()
    {
        return [
            'current',
        ];
    }

    /**
     * Get and format product components
     *
     * @return VE\Electro\Support\Collection;
     */
    public function components()
    {
        if ($this->componentsMutated) {
            return $this->componentsMutated;
        }

        $prices = $this->payload
            ->get('product_components', collect([]))
            ->map(function($component) {
                // Merge component_prices and single product_components item
                // to get flat data model for product_components
                $merged = $component
                    ->get('component_prices')
                    ->map(function($price) use($component) {
                        return $price
                            ->merge($component)
                            ->forget('component_prices');
                    });

                // Replace component_prices with merged data
                $component->put('component_prices', $merged);

                return $component;
            })
            ->pluck('component_prices')
            ->collapse()
            ->sortBy('sort_order');

        $prices = $this->mutateComponents($prices);

        // Map component_prices items to be Component objects
        return $this->componentsMutated =
            (new ComponentCollection($prices))->mapInto(Component::class);
    }

    protected function mutateComponents($items)
    {
        return $items;
    }

    /**
     * Data getters
     */

    public function getProductName()
    {
        return $this->payload->get('product_name');
    }

    public function getDynamicPropertyValue($key)
    {
        $empty = collect([]);

        return $this->payload
            ->get('product_dynamic_properties', $empty)
            ->where('dynamic_property_name', $key)->first(null, $empty)
            ->get('dynamic_property_value', $empty);
    }

    public function getGroup()
    {
        return $this->payload->get('product_group');
    }

    public function getDescription()
    {
        return $this->payload->get('product_descriptions')
            ->where('language', Electro::langId()) // TODO
            ->first()
            ->get('description');
    }

    public function getContractType()
    {
        return $this->payload->get('contract_validity');
    }

    public function getContractDuration()
    {
        $duration = $this->payload->get('contract_validity_duration');
        $durationUnit = $this->payload->get('contract_validity_duration_unit');
        $type = $this->getContractType();

        $durationUnit = Electro::translate($durationUnit);
        $type = Electro::translate($type);

        if ( $duration ) {
            return $type . ' ' . $duration . ' ' . $durationUnit;
        }

        return $type;
    }

    /**
     * Get tariff id / measurement method
     *
     * @return int
     */
    public function getMeasurementMethodId()
    {
        $value = $this->getDynamicPropertyValue('MeasurementMethod')->first();

        return intval($value);
    }

    public function getMeasurementMethodName()
    {
        $key = $this->getMeasurementMethodId();

        $titles = [
            '1' => Electro::translate('common'),
            '2' => Electro::translate('time'),
            '3' => Electro::translate('season')
        ];

        return $titles[$key];
    }

    public function getOrderLink()
    {
        // @TODO: Get base from ENVs
        $base_uri = 'https://193.208.127.194:83';
        $path = '/NewContract/Contract/EndProcess';
        return add_query_arg([
            'selectedProductCode' => $this->getProductName(),
            'culture' => Electro::getLocale(),
            'brand' => $this->isBonusProduct() ? 'SBonus' : '',
        ], $base_uri . $path);
    }

    public function getOrderButtonText()
    {
        // @TODO: Change to use directly _e
        return Electro::translate('order');
    }

    /**
     * Should product be in website
     *
     * @return boolean
     */
    public function isPublic()
    {
        return (bool)
            $this->getDynamicPropertyValue('ContractChannel')->contains('Verkkosivut') &&
            $this->payload->get('public_pricing');
    }

    /**
     * Is Product a bonus product
     *
     * @return boolean
     */
    public function isBonusProduct()
    {
        return $this->payload->get('bonus_product');
    }

    public function isSpotProduct()
    {
        return (bool) $this->payload->get('product_account_group', 0) == 59001;
    }
}
