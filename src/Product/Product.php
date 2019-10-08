<?php

namespace VE\Electro\Product;

use VE\Electro\Electro;
use VE\Electro\Support\Str;
use VE\Electro\Presenters;
use VE\Electro\Product\Collections\PayloadCollection;
use VE\Electro\Product\Collections\ComponentCollection;

class Product
{
    protected $payload;
    protected $context;
    protected $filters = [];

    public function __construct($model, $context)
    {
        $this->payload = (new PayloadCollection($model->payload))->recursive();
        $this->components = $this->payload()
            ->get('product_components');

        $this->context = $context;
    }

    public function payload()
    {
        return $this->payload;
    }

    public function getAllComponents()
    {
        $components = (new ComponentCollection($this->components))
            ->mapInto(ProductComponent::class);

        return $this->context->components($components);
    }

    public function components()
    {
        $comps = $this->getAllComponents();

        foreach($this->filters as $method => $key) {
            $comps = $comps->$method($key, $this->context);
        }

        return $comps;
    }

    public function filter($filters)
    {
        $this->filters = $filters;
        return $this;
    }

    public function getMeta()
    {
        return $this->context->meta();
    }

    public function getRelatedPeriodGroup()
    {
        return 'sf';
    }

    public function getType()
    {
        return $this->context->getType();
    }

    public function isType($type)
    {
        return $this->getType() == $type;
    }

    public function get($key)
    {
        return $this->payload()->get($key);
    }

    public function getDescription()
    {
        return $this->get('product_descriptions')
            ->where('language', Electro::langId()) // TODO
            ->first()
            ->get('description');
    }

    public function getContractType()
    {
        return $this->get('contract_validity');
    }

    public function getContractDuration()
    {
        $duration = $this->get('contract_validity_duration');
        $durationUnit = $this->get('contract_validity_duration_unit');
        $type = $this->getContractType();

        $durationUnit = Electro::translate($durationUnit);
        $type = Electro::translate($type);

        if ( $duration ) {
            return $type . ' ' . $duration . ' ' . $durationUnit;
        }

        return $type;
    }

    public function getDynamicPropertyValue($key)
    {
        $empty = collect([]);

        return $this->payload
            ->get('product_dynamic_properties', $empty)
            ->where('dynamic_property_name', $key)->first(null, $empty)
            ->get('dynamic_property_value', $empty);
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
        $args = array_filter([
            'selectedProductCode' => $this->getProductName(),
            'culture' => Electro::getLocale(),
            'brand' => $this->isBonusProduct() ? 'SBonus' : '',
        ]);

        return add_query_arg($args, $base_uri . $path);
    }

    public function isActive()
    {
        $first =  $this->components()->first();
        if ($first) {
            return $first->isActive();
        }

    }

    public function present() {
        return new Presenters\ProductPresenter($this);
    }

    public function __call($method, $parameters)
    {
        if (Str::startsWith($method, 'get')) {
            $key = Str::replaceFirst('get', '', $method);
            $key = Str::snake($key);

            if ($withProductPrefix = $this->get("product_$key")) {
                return $withProductPrefix;
            }

            return $this->get($key);
        }

        if (Str::startsWith($method, 'is')) {
            $key = Str::replaceFirst('is', '', $method);
            $key = Str::snake($key);

            return (bool) $this->get($key);
        }


    }

}
