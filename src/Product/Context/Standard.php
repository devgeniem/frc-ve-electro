<?php

namespace VE\Electro\Product\Context;

class Standard
{
    public const TYPE = 'standard';

    protected $hasRelatedPeriodGroup = false;

    public function getType()
    {
        return static::TYPE;
    }

    public function components($components)
    {
        return $components;
    }

    public function nextDaysPrior()
    {
        return 0;
    }

    public function hasRelatedPeriodGroup()
    {
        return $this->hasRelatedPeriodGroup;
    }

    public function meta()
    {
        return ['contract_duration'];
    }
}
