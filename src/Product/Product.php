<?php

namespace VE\Electro\Product;

use VE\Electro\Presenters\ProductPresenter;
use VE\Electro\Presenters\Traits\HasPresenter;
use VE\Electro\Product\Collections\PayloadCollection;

abstract class Product
{
    use Traits\HasMeta;
    use Traits\HasComponents;
    use Traits\HasPeriodFilters;
    use Traits\HasAdditionalProducts;

    use HasPresenter;
    protected $presenter = ProductPresenter::class;

    protected $payload;

    protected $type;

    protected $canHavePriceEstimation = false;

    protected $hasRelatedPeriodGroup = false;

    protected $displayContractPeriod = false;

    public function __construct($payload)
    {
        $this->payload = PayloadCollection::make($payload)->recursive();
    }

    public function id()
    {
        return $this->payload->get('product_name');
    }

    public function type()
    {
        return $this->type;
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

    public function getContractType()
    {
        return $this->payload->get('contract_validity');
    }

    public function contractDuration()
    {
        return $this->getContractType();
    }

    public function displayContractPeriod()
    {
        return $this->displayContractPeriod;
    }

    protected function hasRelatedPeriodGroup()
    {
        return $this->hasRelatedPeriodGroup;
    }

    public function canHavePriceEstimation()
    {
        return $this->canHavePriceEstimation;
    }

    public function isBonusProduct()
    {
        return $this->payload->get('bonus_product');
    }

    public function title($lang = true)
    {
        return $this->payload->get('product_descriptions')
            ->where('language', $lang)
            ->first()
            ->get('description');
    }

    public function customerType()
    {
        return $this->getDynamicPropertyValue('CustomerType')->first();
    }

    public function isCustomerType($type)
    {
        return $this->customerType() == $type;
    }

    public function isForConsumers()
    {
        return $this->isCustomerType('Henkilö');
    }

    public function isForBusiness()
    {
        return $this->isCustomerType('Yritys / organisaatio');
    }

    public function campaignCode()
    {
        return $this->getDynamicPropertyValue('CampaignCode')->first();
    }
}
