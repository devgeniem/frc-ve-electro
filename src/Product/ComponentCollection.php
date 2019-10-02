<?php

namespace VE\Electro\Product;

use Carbon\Carbon;

class ComponentCollection extends PayloadCollection
{
    /**
     * Get main prices - usually monthly fee
     *
     * @return $this
     */
    public function typeSecondary()
    {
        return $this->where('sort_order', '<=', 1);
    }

    public function typePrimary()
    {
        return $this->where('sort_order', '>', 1);
    }

    public function period($key)
    {
        // @TODO: Error handling
        $key = 'period' . ucfirst($key);
        return $this->{$key}();
    }

    public function type($key)
    {
        $key = 'type' . ucfirst($key);
        return $this->{$key}();
    }

    protected function filterByDate($date)
    {

        $items =  $this->filter(function($value) use($date) {
            $from = Carbon::parse($value['valid_from']);
            $to = Carbon::parse($value['valid_to']);

            return $date->greaterThanOrEqualTo($from) && $date->lessThan($to);
        });

        return $items;
    }

    public function periodCurrent()
    {
        return $this->filterByDate(Carbon::now());
    }

    public function periodPrimary()
    {
        return $this->periodActive();
    }

    public function periodSecondary()
    {
        $primary = $this->periodPrimary();

        $now = Carbon::now();

        $next = $primary->first()->get('valid_to')->subMonths(6)->startOfMonth();
        $prev = $primary->first()->get('valid_from')->addMonths(2)->endOfMonth();

        if ( $now->greaterThanOrEqualTo($next) ) {
            return $this->periodNext($primary);
        }

        if ( $now->lessThan($prev) ) {
            return $this->periodPrev($primary);
        }

    }

    public function periodActive()
    {
        // @TODO: Get period of relevant to user.
        // E.g. show next period already 14 days before valid_from date.
        $next = Carbon::now()->addDays(14);
        return $this->filterByDate($next);
    }

    public function prevOrNext()
    {
        // @TODO: Figure which side of current's period should be shown

        return $this;
    }

    public function periodNext($by = null)
    {
        if (! $by ) {
            $by = $this->periodCurrent()->first();
        } else {
            $by = $by->first();
        }

        if (! $by) {
            return $this;
        }

        $by = $by->get('valid_to');

        return $this->filter(function($value) use($by) {
            $from = Carbon::parse($value['valid_from']);
            return $from->isSameDay($by);
        });
    }

    public function periodPrev($by = null)
    {
        if (! $by ) {
            $by = $this->periodCurrent()->first();
        } else {
            $by = $by->first();
        }

        if (! $by) {
            return $this;
        }

        $by = $by->get('valid_from');



        return $this->filter(function($value) use($by) {
            $to = Carbon::parse($value['valid_to']);
            return $to->isSameDay($by);
        });
    }
}

