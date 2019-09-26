<?php

namespace VE\Electro\Product\Variations;

use Carbon\Carbon;

class Temporary extends Standard
{
    public function getType()
    {
        return 'temporary';
    }

    protected function mutateComponents($items)
    {
        $items = $items->map(function($item) {
            $from = Carbon::parse($item['valid_from'])
                ->startOfMonth()
                ->addMonths(6)
                ->endOfMonth()
                ->startOfDay();

            $item['valid_to'] = $from->toString();
            return $item;
        });

        return $items;
    }

    public function periods()
    {
        return [
            'current',
            'next',
        ];
    }

    public function togglePeriod($active)
    {
        $periods = $this->periods();
        $items = array_filter($periods, function($key) use($active) {
            return $key !== $active;
        });
        $items = reset($items);

        return $items;
    }
}
