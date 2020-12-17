<?php

namespace VE\Electro\Product\Variants;

use VE\Electro\Product\Enums\PricePeriod;
use VE\Electro\Product\Product;

class Temporary extends Product
{
    protected $type = 'temporary';

    protected $canHavePriceEstimation = true;

    protected $hasRelatedPeriodGroup = true;

    protected $displayContractPeriod = true;

    public function pricePeriodActive($from, $to)
    {
        return $this->isPricePeriodUpcoming($from, $to);
    }

    public function pricePeriodKey($from, $to)
    {
        if ($this->isPricePeriodCurrent($from, $to)) {
            return PricePeriod::CURRENT;
        }

        if ($this->isPricePeriodUpcoming($from, $to)) {
            return PricePeriod::UPCOMING;
        }

        if ($this->isPricePeriodPast($from, $to)) {
            return PricePeriod::PAST;
        }

        if ($this->isPricePeriodNext($from, $to)) {
            return PricePeriod::NEXT;
        }
    }

    protected function isPricePeriodUpcoming($from, $to)
    {
        $referenceDate = $this->now()->addDays(14);

        return $referenceDate->greaterThanOrEqualTo($from)
            && $referenceDate->lessThan($to);
    }

    protected function isPricePeriodPast($from, $to)
    {
        $referenceDate = $this->now()->subMonths(2);

        return $referenceDate->greaterThanOrEqualTo($from)
            && $referenceDate->lessThan($to);
    }

    protected function isPricePeriodNext($from, $to)
    {
        $referenceDate = $this->now()->addMonth(1);

        return $referenceDate->greaterThanOrEqualTo($from)
            && $referenceDate->lessThan($to);
    }

    public function pricePeriodRelated($from, $to)
    {
        return $this->isPricePeriodNext($from, $to)
            || $this->isPricePeriodCurrent($from, $to);
    }

    public function filterPricePeriodFrom($date)
    {
        /**
         * Force price period start date to be
         * 1.1. or 1.7.
         */

        if ($date->get('month') < 7) {
            return $date->month(1)->day(1);
        }

        if ($date->get('month') >= 7) {
            return $date->month(7)->day(1);
        }

        return $date;
    }

    public function filterPricePeriodTo($date, $from = null)
    {
        /**
         * Force  price period end date to be
         * half year after start date
         */

        if ($from) {
            return $from->addMonths(6)->subDay()->endOfMonth()->startOfDay();
        }

        return $date;
    }
}
