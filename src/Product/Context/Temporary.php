<?php

namespace VE\Electro\Product\Context;

use Carbon\Carbon;

class Temporary extends Standard
{
    public const TYPE = 'temporary';

    protected $hasRelatedPeriodGroup = true;

    public function components($components)
    {
        return $components->map(function($item) {
            $from = Carbon::parse($item['valid_from'])
                ->startOfMonth()
                ->addMonths(6)
                ->endOfMonth();

            $item['valid_to'] = $from->toString();
            return $item;
        });
    }

    public function nextDaysPrior()
    {
        return 14;
    }

    public function meta()
    {
        return [
            'contract_duration',
            'price_period',
        ];
    }
}
