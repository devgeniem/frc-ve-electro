<?php

namespace VE\Electro\Product\Context;

class Tariff extends Standard
{
    public const TYPE = 'tariff';

    protected $hasRelatedPeriodGroup = true;

    public function meta()
    {
        return ['price_period_from'];
    }
}
