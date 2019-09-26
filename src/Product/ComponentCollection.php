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
    public function main()
    {
        return $this->where('sort_order', '<=', 1);
    }

    public function items()
    {
        return $this->where('sort_order', '>', 1);
    }

    public function period($key)
    {
        // @TODO: Error handling
        return $this->$key();
    }

    public function current()
    {
        $now = Carbon::now();

        return $this->filter(function($value) use($now) {

            $from = Carbon::parse($value['valid_from']);
            $to = Carbon::parse($value['valid_to']);

            return $now->greaterThanOrEqualTo($from) && $now->lessThan($to);
        });
    }

    public function active()
    {
        // @TODO: Get period of relevant to user.
        // E.g. show next period already 14 days before valid_from date.
        return $this;
    }

    public function prevOrNext()
    {
        // @TODO: Figure which side of current's period should be shown
        return $this;
    }

    public function next()
    {
        $currentEnd = $this->current()->first()->get('valid_to');

        return $this->filter(function($value) use($currentEnd) {
            $from = Carbon::parse($value['valid_from']);
            return $from->greaterThanOrEqualTo($currentEnd);
        });
    }

    public function prev()
    {
        $currentFrom = $this->current()->first()->get('valid_from');

        return $this->filter(function($value) use($currentFrom) {
            $to = Carbon::parse($value['valid_to']);
            return $to->lessThan($currentFrom);
        });
    }
}

