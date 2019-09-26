<?php

namespace VE\Electro\EnerimCIS;

class Code
{
    public const CURRENCY_EUR = 5001;
    public const CURRENCY_CENT = 5002;
    public const CURRENCY_SEK = 5003;
    public const CURRENCY_ORE = 5004;

    public const LANG_FI = 32001;
    public const LANG_EN = 32002;
    public const LANG_NO = 32003;
    public const LANG_SV = 32004;

    public const DECIMALS_0 = 135001;
    public const DECIMALS_1 = 135002;
    public const DECIMALS_2 = 135003;

    // Not included in API

    public const GROUP_PACKAGE = 'Pakettisähkö';
    public const TYPE_TEMPORARY = 'Puolivuotistuote';

    protected static $values = [
        // Currency
        '5001' => 'EUR',
        '5002' => 'cent',
        '5003' => 'SEK',
        '5004' => 'öre',

        // Language
        '32001' => 'fi',
        '32002' => 'en',
        '32003' => 'no',
        '32004' => 'sv',

        // PriceDecimals
        '135001' => 0,
        '135002' => 1,
        '135003' => 2,
        '135004' => 3,
        '135005' => 4,
        '135006' => 5,
        '135007' => 6,
        '135008' => 7,
        '135009' => 8,
        '135010' => 9,
        '135011' => 10,

        // BecomeValid
        '156001' => true, // Without delay
        '156002' => false, // Delayed

        // ProductType
        '90001' => 'main',
        '90002' => 'additional',

        // Validity
        '155001' => 'permanent',
        '155002' => 'temporary',

        // ValidPeriodUnit
        '154001' => 'month',
        '154002' => 'year',
        '154003' => 'day',

        // VatRate
        '78001' => 'standard',
        '78002' => 'zero',
        '78003' => 'reduced',
        '78004' => 'super_reduced',

        // YesNoType
        '142001' => true,
        '142002' => false,
    ];

    public static function get($key) {
        if ( array_key_exists($key, static::$values) ) {
            return static::$values[$key];
        }
        return $key;
    }
}
