<?php

namespace VE\Electro;

use VE\Electro\EnerimCIS\Code;

class Electro
{
    public static function getLocale()
    {
        return mb_substr(get_locale(), 0, 2);
    }

    public static function langId()
    {
        $locale = static::getLocale();
        $values = [
            'fi' => Code::LANG_FI,
            'en' => Code::LANG_EN,
            'sv' => Code::LANG_SV,
        ];
        return $values[$locale];
    }

    public static function translate($key)
    {
        // @TODO: Maybe use WP translation functions
        $locale = static::getLocale();

        $translations = [
            'month' => [
                'fi' => 'kk',
                'en' => 'month',
                'sv' => 'mån',
            ],
            'temporary' => [
                'fi' => 'määräaikainen',
                'en' => 'fixed-term',
                'sv' => 'visstids',
            ],
            'permanent' => [
                'fi' => 'toistaiseksi',
                'en' => 'until further notice',
                'sv' => 'tillsvidare',
            ],
            'common' => [
                'fi' => 'yleissähkö',
                'en' => 'common',
                'sv' => 'allmän el',
            ],
            'time' => [
                'fi' => 'aikasähkö',
                'en' => 'time',
                'sv' => 'tidsel',
            ],
            'season' => [
                'fi' => 'kausisähkö',
                'en' => 'season',
                'sv' => 'säsongel',
            ],
            'order' => [
                'fi' => 'Tilaa',
                'en' => 'Order',
                'sv' => 'Beställa',
            ],
            'price_period' => [
                'fi' => 'Hintajakso',
                'en' => 'Price period',
                'sv' => 'Prisperiod',
            ],
            'contract_duration' => [
                'fi' => 'Sopimuksen kesto',
                'en' => 'Contract duration',
                'sv' => 'Avtalets längd',
            ]
        ];

        return $translations[$key][$locale] ?? $key;
    }
}
