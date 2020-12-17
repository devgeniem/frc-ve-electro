<?php

namespace Electro;

use VE\Electro\Electro;
use VE\Electro\Product\ProductAdditionalRepository;
use VE\Electro\ProductGroup\ProductGroupRepository;

const METERING = [
    'COMMON' => 1,
    'TIME' => 2,
    'SEASON' => 3,
];

const CONSUMPTION = [
    'COMMON' => 'YLEIS',
    'TIME_DAY' => 'AIKA_PAIVA',
    'TIME_NIGHT' => 'AIKA_YO',
    'SEASON_WINTER' => 'KAUSI_TALVIP',
    'SEASON_OTHER' => 'KAUSI_MUU',
];

const HOUSE_TYPE = [
    'APARTMENT' => 1,
    'ROW_HOUSE' => 2,
    'TOWN_HOUSE' => 3,
    'OTHER' => 4,
];

function getProductGroup($id)
{
    return ProductGroupRepository::get($id)->present();
}

function getProductGroups($groups)
{
    if (! $groups) {
        return [];
    }

    return array_map(function($id) {
        return getProductGroup($id);
    }, $groups);
}

function getGeneralData($type) {
    return [
        'METERING' => METERING,
        'CONSUMPTION' => CONSUMPTION,
        'HOUSE_TYPE' => HOUSE_TYPE,
        'TYPE' => $type,
    ];
}

function getHouseTypes() {
    return [
        [
            'label' => Electro::translate('HOUSE_TYPE.APARTMENT'),
            'value' => HOUSE_TYPE['APARTMENT'],
            'data' => [
                'type' => HOUSE_TYPE['APARTMENT'],
                'metering' => [
                    METERING['COMMON'],
                ],
                'metering-default' => METERING['COMMON'],
                'consumptions' => [
                    CONSUMPTION['COMMON'] => 2000,
                ],
                'square-meters' => [
                    [
                        'from' => 0,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 1000],
                        ],
                    ],
                    [
                        'from' => 40,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 1500],
                        ],
                    ],
                    [
                        'from' => 60,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 2000],
                        ],
                    ],
                    [
                        'from' => 80,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 3000],
                        ],
                    ],
                    [
                        'from' => 100,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 4000],
                        ],
                    ],
                    [
                        'from' => 120,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 5000],
                        ],
                    ],
                    [
                        'from' => 140,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 6000],
                        ],
                    ],
                    [
                        'from' => 160,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 7000],
                        ],
                    ],
                    [
                        'from' => 180,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 8000],
                        ],
                    ],
                    [
                        'from' => 200,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 10000],
                        ],
                    ],
                ],
            ],
        ],
        [
            'label' =>  Electro::translate('HOUSE_TYPE.ROW_HOUSE'),
            'value' => HOUSE_TYPE['ROW_HOUSE'],
            'data' => [
                'type' => HOUSE_TYPE['ROW_HOUSE'],
                'metering' => [
                    METERING['COMMON'],
                    METERING['TIME'],
                    METERING['SEASON'],
                ],
                'metering-default' => METERING['TIME'],
                'consumptions' => [
                    CONSUMPTION['COMMON'] => 4200,
                    CONSUMPTION['TIME_DAY'] => 1000,
                    CONSUMPTION['TIME_NIGHT'] => 3200,
                    CONSUMPTION['SEASON_WINTER'] => 1680,
                    CONSUMPTION['SEASON_OTHER'] => 2520,
                ],
                'square-meters' => [
                    [
                        'from' => 0,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 3000],
                            ['key' => CONSUMPTION['TIME_DAY'], 'value' => 1800],
                            ['key' => CONSUMPTION['TIME_NIGHT'], 'value' => 1200],
                            ['key' => CONSUMPTION['SEASON_WINTER'], 'value' => 1200],
                            ['key' => CONSUMPTION['SEASON_OTHER'], 'value' => 1800],
                        ],
                    ],
                    [
                        'from' => 60,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 5000],
                            ['key' => CONSUMPTION['TIME_DAY'], 'value' => 3000],
                            ['key' => CONSUMPTION['TIME_NIGHT'], 'value' => 2000],
                            ['key' => CONSUMPTION['SEASON_WINTER'], 'value' => 2000],
                            ['key' => CONSUMPTION['SEASON_OTHER'], 'value' => 3000],
                        ],
                    ],
                    [
                        'from' => 80,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 8000],
                            ['key' => CONSUMPTION['TIME_DAY'], 'value' => 4800],
                            ['key' => CONSUMPTION['TIME_NIGHT'], 'value' => 3200],
                            ['key' => CONSUMPTION['SEASON_WINTER'], 'value' => 3200],
                            ['key' => CONSUMPTION['SEASON_OTHER'], 'value' => 4800],
                        ],
                    ],
                    [
                        'from' => 100,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 10000],
                            ['key' => CONSUMPTION['TIME_DAY'], 'value' => 6000],
                            ['key' => CONSUMPTION['TIME_NIGHT'], 'value' => 4000],
                            ['key' => CONSUMPTION['SEASON_WINTER'], 'value' => 4000],
                            ['key' => CONSUMPTION['SEASON_OTHER'], 'value' => 6000],
                        ],
                    ],
                    [
                        'from' => 120,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 12000],
                            ['key' => CONSUMPTION['TIME_DAY'], 'value' => 7200],
                            ['key' => CONSUMPTION['TIME_NIGHT'], 'value' => 4800],
                            ['key' => CONSUMPTION['SEASON_WINTER'], 'value' => 4800],
                            ['key' => CONSUMPTION['SEASON_OTHER'], 'value' => 7200],
                        ],
                    ],
                    [
                        'from' => 140,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 14000],
                            ['key' => CONSUMPTION['TIME_DAY'], 'value' => 8400],
                            ['key' => CONSUMPTION['TIME_NIGHT'], 'value' => 5600],
                            ['key' => CONSUMPTION['SEASON_WINTER'], 'value' => 5600],
                            ['key' => CONSUMPTION['SEASON_OTHER'], 'value' => 8400],
                        ],
                    ],
                    [
                        'from' => 160,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 16000],
                            ['key' => CONSUMPTION['TIME_DAY'], 'value' => 9600],
                            ['key' => CONSUMPTION['TIME_NIGHT'], 'value' => 6400],
                            ['key' => CONSUMPTION['SEASON_WINTER'], 'value' => 6400],
                            ['key' => CONSUMPTION['SEASON_OTHER'], 'value' => 9600],
                        ],
                    ],
                    [
                        'from' => 180,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 18000],
                            ['key' => CONSUMPTION['TIME_DAY'], 'value' => 10800],
                            ['key' => CONSUMPTION['TIME_NIGHT'], 'value' => 7200],
                            ['key' => CONSUMPTION['SEASON_WINTER'], 'value' => 7200],
                            ['key' => CONSUMPTION['SEASON_OTHER'], 'value' => 10800],
                        ],
                    ],
                    [
                        'from' => 200,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 20000],
                            ['key' => CONSUMPTION['TIME_DAY'], 'value' => 12000],
                            ['key' => CONSUMPTION['TIME_NIGHT'], 'value' => 8000],
                            ['key' => CONSUMPTION['SEASON_WINTER'], 'value' => 8000],
                            ['key' => CONSUMPTION['SEASON_OTHER'], 'value' => 12000],
                        ],
                    ],
                ],
            ],
        ],
        [
            'label' => Electro::translate('HOUSE_TYPE.TOWN_HOUSE'),
            'value' => HOUSE_TYPE['TOWN_HOUSE'],
            'data' => [
                'type' => HOUSE_TYPE['TOWN_HOUSE'],
                'metering' => [
                    METERING['COMMON'],
                    METERING['TIME'],
                    METERING['SEASON'],
                ],
                'metering-default' => METERING['TIME'],
                'consumptions' => [
                    CONSUMPTION['COMMON'] => 8800,
                    CONSUMPTION['TIME_DAY'] => 2000,
                    CONSUMPTION['TIME_NIGHT'] => 6800,
                    CONSUMPTION['SEASON_WINTER'] => 3520,
                    CONSUMPTION['SEASON_OTHER'] => 5280,
                ],
                'square-meters' => [
                    [
                        'from' => 0,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 17000],
                            ['key' => CONSUMPTION['TIME_DAY'], 'value' => 10200],
                            ['key' => CONSUMPTION['TIME_NIGHT'], 'value' => 6800],
                            ['key' => CONSUMPTION['SEASON_WINTER'], 'value' => 6800],
                            ['key' => CONSUMPTION['SEASON_OTHER'], 'value' => 10200],
                        ],
                    ],
                    [
                        'from' => 120,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 19000],
                            ['key' => CONSUMPTION['TIME_DAY'], 'value' => 11400],
                            ['key' => CONSUMPTION['TIME_NIGHT'], 'value' => 7600],
                            ['key' => CONSUMPTION['SEASON_WINTER'], 'value' => 7600],
                            ['key' => CONSUMPTION['SEASON_OTHER'], 'value' => 11400],
                        ],
                    ],
                    [
                        'from' => 150,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 21000],
                            ['key' => CONSUMPTION['TIME_DAY'], 'value' => 12600],
                            ['key' => CONSUMPTION['TIME_NIGHT'], 'value' => 8400],
                            ['key' => CONSUMPTION['SEASON_WINTER'], 'value' => 8400],
                            ['key' => CONSUMPTION['SEASON_OTHER'], 'value' => 12600],
                        ],
                    ],
                    [
                        'from' => 180,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 23000],
                            ['key' => CONSUMPTION['TIME_DAY'], 'value' => 13800],
                            ['key' => CONSUMPTION['TIME_NIGHT'], 'value' => 9200],
                            ['key' => CONSUMPTION['SEASON_WINTER'], 'value' => 9200],
                            ['key' => CONSUMPTION['SEASON_OTHER'], 'value' => 13800],
                        ],
                    ],
                    [
                        'from' => 210,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 25000],
                            ['key' => CONSUMPTION['TIME_DAY'], 'value' => 15000],
                            ['key' => CONSUMPTION['TIME_NIGHT'], 'value' => 10000],
                            ['key' => CONSUMPTION['SEASON_WINTER'], 'value' => 10000],
                            ['key' => CONSUMPTION['SEASON_OTHER'], 'value' => 15000],
                        ],
                    ],
                    [
                        'from' => 250,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 27000],
                            ['key' => CONSUMPTION['TIME_DAY'], 'value' => 16200],
                            ['key' => CONSUMPTION['TIME_NIGHT'], 'value' => 10800],
                            ['key' => CONSUMPTION['SEASON_WINTER'], 'value' => 10800],
                            ['key' => CONSUMPTION['SEASON_OTHER'], 'value' => 16200],
                        ],
                    ],
                ],
            ],
        ],
        [
            'label' =>  Electro::translate('HOUSE_TYPE.OTHER'),
            'value' => HOUSE_TYPE['OTHER'],
            'data' => [
                'type' => HOUSE_TYPE['OTHER'],
                'metering' => [
                    METERING['COMMON'],
                    METERING['TIME'],
                    METERING['SEASON'],
                ],
                'metering-default' => METERING['SEASON'],
                'consumptions' => [
                    CONSUMPTION['COMMON'] => 2000,
                    CONSUMPTION['TIME_DAY'] => 1200,
                    CONSUMPTION['TIME_NIGHT'] => 800,
                    CONSUMPTION['SEASON_WINTER'] => 3520,
                    CONSUMPTION['SEASON_OTHER'] => 5280,
                ],
                'square-meters' => [
                    [
                        'from' => 0,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 500],
                            ['key' => CONSUMPTION['TIME_DAY'], 'value' => 300],
                            ['key' => CONSUMPTION['TIME_NIGHT'], 'value' => 200],
                            ['key' => CONSUMPTION['SEASON_WINTER'], 'value' => 200],
                            ['key' => CONSUMPTION['SEASON_OTHER'], 'value' => 300],
                        ],
                    ],
                    [
                        'from' => 60,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 1000],
                            ['key' => CONSUMPTION['TIME_DAY'], 'value' => 600],
                            ['key' => CONSUMPTION['TIME_NIGHT'], 'value' => 400],
                            ['key' => CONSUMPTION['SEASON_WINTER'], 'value' => 400],
                            ['key' => CONSUMPTION['SEASON_OTHER'], 'value' => 600],
                        ],
                    ],
                    [
                        'from' => 80,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 2000],
                            ['key' => CONSUMPTION['TIME_DAY'], 'value' => 1200],
                            ['key' => CONSUMPTION['TIME_NIGHT'], 'value' => 800],
                            ['key' => CONSUMPTION['SEASON_WINTER'], 'value' => 800],
                            ['key' => CONSUMPTION['SEASON_OTHER'], 'value' => 1200],
                        ],
                    ],
                    [
                        'from' => 100,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 3000],
                            ['key' => CONSUMPTION['TIME_DAY'], 'value' => 1800],
                            ['key' => CONSUMPTION['TIME_NIGHT'], 'value' => 1200],
                            ['key' => CONSUMPTION['SEASON_WINTER'], 'value' => 1200],
                            ['key' => CONSUMPTION['SEASON_OTHER'], 'value' => 1800],
                        ],
                    ],
                    [
                        'from' => 120,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 4000],
                            ['key' => CONSUMPTION['TIME_DAY'], 'value' => 2400],
                            ['key' => CONSUMPTION['TIME_NIGHT'], 'value' => 1600],
                            ['key' => CONSUMPTION['SEASON_WINTER'], 'value' => 1600],
                            ['key' => CONSUMPTION['SEASON_OTHER'], 'value' => 2400],
                        ],
                    ],
                    [
                        'from' => 140,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 5000],
                            ['key' => CONSUMPTION['TIME_DAY'], 'value' => 3000],
                            ['key' => CONSUMPTION['TIME_NIGHT'], 'value' => 2000],
                            ['key' => CONSUMPTION['SEASON_WINTER'], 'value' => 2000],
                            ['key' => CONSUMPTION['SEASON_OTHER'], 'value' => 3000],
                        ],
                    ],
                    [
                        'from' => 160,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 6000],
                            ['key' => CONSUMPTION['TIME_DAY'], 'value' => 3600],
                            ['key' => CONSUMPTION['TIME_NIGHT'], 'value' => 2400],
                            ['key' => CONSUMPTION['SEASON_WINTER'], 'value' => 2400],
                            ['key' => CONSUMPTION['SEASON_OTHER'], 'value' => 3600],
                        ],
                    ],
                    [
                        'from' => 180,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 7000],
                            ['key' => CONSUMPTION['TIME_DAY'], 'value' => 4200],
                            ['key' => CONSUMPTION['TIME_NIGHT'], 'value' => 2800],
                            ['key' => CONSUMPTION['SEASON_WINTER'], 'value' => 2800],
                            ['key' => CONSUMPTION['SEASON_OTHER'], 'value' => 4200],
                        ],
                    ],
                    [
                        'from' => 200,
                        'values' => [
                            ['key' => CONSUMPTION['COMMON'], 'value' => 8000],
                            ['key' => CONSUMPTION['TIME_DAY'], 'value' => 4800],
                            ['key' => CONSUMPTION['TIME_NIGHT'], 'value' => 3200],
                            ['key' => CONSUMPTION['SEASON_WINTER'], 'value' => 3200],
                            ['key' => CONSUMPTION['SEASON_OTHER'], 'value' => 4800],
                        ],
                    ],
                ],
            ],
        ],
    ];
}

function getMeteringTypes() {
    return [
        [
            'label' => Electro::translate('METERING.COMMON'),
            'value' => METERING['COMMON'],
        ],
        [
            'label' => Electro::translate('METERING.TIME'),
            'value' => METERING['TIME'],
        ],
        [
            'label' => Electro::translate('METERING.SEASON'),
            'value' => METERING['SEASON'],
        ],
    ];
}

function getConsumptionInputs() {
    return [
        [
            'metering' => METERING['COMMON'],
            'inputs' => [
                [
                    'name' => CONSUMPTION['COMMON'],
                    'label' => Electro::translate('CONSUMPTION.COMMON'),
                ],
            ],
        ],
        [
            'metering' => METERING['TIME'],
            'inputs' => [
                [
                    'name' => CONSUMPTION['TIME_DAY'],
                    'label' => Electro::translate('CONSUMPTION.TIME_DAY'),
                ],
                [
                    'name' => CONSUMPTION['TIME_NIGHT'],
                    'label' => Electro::translate('CONSUMPTION.TIME_NIGHT'),
                ],
            ],
        ],
        [
            'metering' => METERING['SEASON'],
            'inputs' => [
                [
                    'name' => CONSUMPTION['SEASON_WINTER'],
                    'label' => Electro::translate('CONSUMPTION.SEASON_WINTER'),
                ],
                [
                    'name' => CONSUMPTION['SEASON_OTHER'],
                    'label' => Electro::translate('CONSUMPTION.SEASON_OTHER'),
                ],
            ],
        ],
    ];
}

/**
 * B2B
 */

function getMeteringTypesB2b() {
    return [
        [
            'label' => Electro::translate('METERING.COMMON'),
            'value' => METERING['COMMON'],
        ],
    ];
}

function getConsumptionInputsB2b() {
    return [
        [
            'metering' => METERING['COMMON'],
            'inputs' => [
                [
                    'name' => CONSUMPTION['COMMON'],
                    'label' => Electro::translate('CONSUMPTION.COMMON'),
                ],
            ],
        ],
    ];
}

function getCompanyTypes() {
    return [
        [
            'label' => 'Yritykset',
            'value' => 1,
            'data' => [
                'type' => 1,
                'metering' => [
                    METERING['COMMON'],
                ],
                'metering-default' => METERING['COMMON'],
                'consumptions' => [
                    CONSUMPTION['COMMON'] => 2000,
                ],
            ],
        ],
    ];
}

function getAdditionalProducts() {
    return ProductAdditionalRepository::all()->map->present();
}
