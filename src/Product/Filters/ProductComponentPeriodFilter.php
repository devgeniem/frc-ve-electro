<?php

namespace VE\Electro\Product\Filters;

use Carbon\Carbon;

class ProductComponentPeriodFilter
{
    protected $collection;
    protected $context;

    public function __construct($collection, $context)
    {
        $this->collection = $collection;
        $this->context = $context;
    }

    protected function byDate($date)
    {
        return $this->collection->filter(function($value) use($date) {
            $from = Carbon::parse($value['valid_from']);
            $to = Carbon::parse($value['valid_to']);

            return $date->greaterThanOrEqualTo($from) && $date->lessThan($to);
        });
    }

    public function current()
    {
        return $this->byDate(Carbon::now());
    }

    public function active()
    {
        // Get relevant period.
        // E.g. show next period already 14 days before valid_from date.
        $next = Carbon::now()->addDays(
            $this->context->nextDaysPrior()
        );
        return $this->byDate($next);
    }

    public function primary()
    {
        return $this->active();
    }

    public function secondary()
    {
        $primary = $this->primary();

        if (! $primary->all()) {
            return;
        }

        $now = Carbon::now();

        $next = $primary->first()->get('valid_to')->subMonths(1)->startOfMonth();
        $prev = $primary->first()->get('valid_from')->addMonths(2)->startOfMonth();

        if ( $now->greaterThanOrEqualTo($next) ) {
            return $this->next($primary);
        }

        if ( $now->lessThan($prev) ) {
            return $this->previous($primary);
        }
    }

    public function next($by = null)
    {
        if (! $by ) {
            $by = $this->current()->first();
        } else {
            $by = $by->first();
        }

        if (! $by) {
            return $this->collection;
        }

        $by = $by->get('valid_to');

        return $this->collection->filter(function($value) use($by) {
            $from = Carbon::parse($value['valid_from']);
            return $from->isSameDay($by);
        });
    }

    public function previous($by = null)
    {
        if (! $by ) {
            $by = $this->current()->first();
        } else {
            $by = $by->first();
        }

        if (! $by) {
            return $this->collection;
        }

        $by = $by->get('valid_from');

        return $this->collection->filter(function($value) use($by) {
            $to = Carbon::parse($value['valid_to']);
            return $to->isSameDay($by);
        });
    }
}
