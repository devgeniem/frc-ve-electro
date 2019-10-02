<?php

namespace VE\Electro\Product\Variations;

use Carbon\Carbon;

class Temporary extends Standard
{
    public function getType()
    {
        return 'temporary';
    }

    public function components()
    {
        
        $items = $this->components
            ->period($this->getFilter('period'));
        
        if (! $items ) {
            return;
        }

        return $items->map(function($item) {
                $from = Carbon::parse($item['valid_from'])
                    ->startOfMonth()
                    ->addMonths(6)
                    ->endOfMonth();

                $item['valid_to'] = $from->toString();
                return $item;
            });
    }

    public function periods()
    {
        if ( $this->components->period('secondary') ) {
            return [
                'primary',
                'secondary',
            ];
        }
        return ['primary'];
    }

    public function shouldDisplayPrev()
    {
        $now = Carbon::now();
        $current = $this->components()->current()->first();

        if (! $current) {
            return false;
        }

        $current = $current->get('valid_from')->addMonths(2);
        return $now->lessThan($current);
    }

    public function shouldDisplayCurrent()
    {
        $now = Carbon::now();
        $current = $this->components()->current()->first();

        if (! $current) {
            return false;
        }

        $current = $current->get('valid_to')->subDays(14);
        return $now->greaterThanOrEqualTo($current);
    }

    public function shouldDisplayNext()
    {
        $now = Carbon::now();
        $current = $this->components()->current()->first();

        if (! $current) {
            return false;
        }

        $current = $current->get('valid_to')->subMonths(1);
        return $now->greaterThanOrEqualTo($current) && ! $this->shouldDisplayCurrent();
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

    public function shouldDisplayPeriod()
    {
        return $this->payload->get('protection_method') === 'Puolivuotistuote';
    }

    public function getMeta()
    {
        return [
            'contract_duration',
            'price_period',
        ];
    }
}
