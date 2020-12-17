<?php

namespace VE\Electro\Product\Traits;

use Carbon\CarbonImmutable;
use VE\Electro\Product\Enums\PricePeriod;
use VE\Electro\Product\Enums\PricePeriodType;

trait HasPeriodFilters
{
    protected function now()
    {
        if (env('CARBON_TEST_NOW')) {
            CarbonImmutable::setTestNow(env('CARBON_TEST_NOW'));
        }

        return CarbonImmutable::now();
    }

    public function pricePeriod($from, $to)
    {
        if ($this->pricePeriodActive($from, $to)) {
            return PricePeriodType::ACTIVE;
        }

        if ($this->pricePeriodRelated($from, $to)) {
            return PricePeriodType::RELATED;
        }
    }

    public function pricePeriodKey($from, $to)
    {
        if ($this->isPricePeriodCurrent($from, $to)) {
            return PricePeriod::CURRENT;
        }
    }

    public function isPricePeriodCurrent($from, $to) {
        $date = $this->now();

        return $date->greaterThanOrEqualTo($from) && $date->lessThan($to);
    }

    public function pricePeriodActive($from, $to)
    {
        return $this->isPricePeriodCurrent($from, $to);
    }

    public function pricePeriodRelated($from, $to)
    {
        return;
    }

    public function filterPricePeriodFrom($date)
    {
        return $date;
    }

    public function filterPricePeriodTo($date, $from = null)
    {
        return $date;
    }
}
