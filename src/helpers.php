<?php

use VE\Electro\Electro;
use VE\Electro\Support\Arr;
use VE\Electro\Support\Collection;
use VE\Electro\Support\Str;

if (! function_exists('collect')) {
    /**
     * Create a collection from the given value.
     *
     * @param  mixed  $value
     * @return \Illuminate\Support\Collection
     */
    function collect($value = null)
    {
        return new Collection($value);
    }
}

if (! function_exists('data_get')) {
    /**
     * Get an item from an array or object using "dot" notation.
     *
     * @param  mixed   $target
     * @param  string|array|int  $key
     * @param  mixed   $default
     * @return mixed
     */
    function data_get($target, $key, $default = null)
    {
        if (is_null($key)) {
            return $target;
        }

        $key = is_array($key) ? $key : explode('.', $key);

        while (! is_null($segment = array_shift($key))) {
            if ($segment === '*') {
                if ($target instanceof Collection) {
                    $target = $target->all();
                } elseif (! is_array($target)) {
                    return value($default);
                }

                $result = [];

                foreach ($target as $item) {
                    $result[] = data_get($item, $key);
                }

                return in_array('*', $key) ? Arr::collapse($result) : $result;
            }

            if (Arr::accessible($target) && Arr::exists($target, $segment)) {
                $target = $target[$segment];
            } elseif (is_object($target) && isset($target->{$segment})) {
                $target = $target->{$segment};
            } else {
                return value($default);
            }
        }

        return $target;
    }
}

if (! function_exists('value')) {
    /**
     * Return the default value of the given value.
     *
     * @param  mixed  $value
     * @return mixed
     */
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

function get_product_group($id)
{
    return VE\Electro\ProductGroup\ProductGroupRepository::get($id);
}

function get_basic_product_types() {
    return [
        [
            'title'    => Str::ucfirst(Electro::translate('common')),
            'subtitle' => Electro::translate('all_households'),
        ],
        [
            'title'    => Str::ucfirst(Electro::translate('time')),
            'subtitle' => Electro::translate('electric_heating'),
        ],
        [
            'title'    => Str::ucfirst(Electro::translate('season')),
            'subtitle' => Electro::translate('electric_heating'),
        ]
    ];
}

function get_comparison_inputs() {
    return [
        'inputs' => [
            [
                'metering_unit' => 1,
                'labels' => [
                    [
                        'label' => Electro::translate('energy'),
                        'value' => 2000
                    ]
                ]
            ],
            [
                'metering_unit' => 2,
                'labels' => [
                    [
                        'label' => Electro::translate('day_energy'),
                        'value' => 1200
                    ],
                    [
                        'label' => Electro::translate('night_energy'),
                        'value' => 800
                    ]
                ]
            ],
            [
                'metering_unit' => 3,
                'labels' => [
                    [
                        'label' => Electro::translate('winter_day_energy'),
                        'value' => 800
                    ],
                    [
                        'label' => Electro::translate('other_time_energy'),
                        'value' => 1200
                    ]
                ]
            ],
        ],
        'count' => Electro::translate('count')
    ];
}

function get_product_group_types($groups) {
    $types = array_map(function ($group) {
        return $group->getType();
    }, $groups);
    $types = array_unique($types);
    $list = implode(' ', $types);
    return $list;
}
