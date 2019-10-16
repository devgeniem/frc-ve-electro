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
            ],
            'all_households' => [
                'fi' => 'Kaikki kodit',
                'en' => 'All households',
                'sv' => 'Alla hushåll',
            ],
            'electric_heating' => [
                'fi' => 'Sähkölämmittäjät',
                'en' => 'Electric heating',
                'sv' => 'Elvärmning',
            ],
            'vat_0' => [
                'fi' => 'Näytä hinta ilman arvonlisäveroa (alv 0 %)',
                'en' => 'Show prices without VAT (0 %)',
                'sv' => 'Visa priser utan moms (0 %)',
            ],
            'vat_24' => [
                'fi' => 'Näytä hinta arvonlisäverolla (alv 24 %)',
                'en' => 'Show prices with VAT (24 %)',
                'sv' => 'Visa priser med moms (24 %)',
            ],
            'spot' => [
                'fi' => '+  Vaihtuva Spot-hinta',
                'en' => '+ Spot price',
                'sv' => '+ Spot priset',
            ],
            'from' => [
                'fi' => '%s alkaen',
                'en' => 'from %s',
                'sv' => 'från %s',
            ],
            'energy' => [
                'fi' => 'Energia',
                'en' => 'Energy',
                'sv' => 'Energi',
            ],
            'day_energy' => [
                'fi' => 'Päiväenergia',
                'en' => 'Day Energy',
                'sv' => 'Dag energi',
            ],
            'night_energy' => [
                'fi' => 'Yöenergia',
                'en' => 'Night Energy',
                'sv' => 'Natt energi',
            ],
            'winter_day_energy' => [
                'fi' => 'Talvipäiväenergia',
                'en' => 'Winter day energy',
                'sv' => 'Vinterdag energi',
            ],
            'other_time_energy' => [
                'fi' => 'Muun ajan energia',
                'en' => 'Other Time energy',
                'sv' => 'Annan tidsenergi',
            ],
            'count' => [
                'fi' => 'Laske',
                'en' => 'Count',
                'sv' => 'Räkna',
            ]
        ];

        return $translations[$key][$locale] ?? $key;
    }

    public static function sopaLink($args = [])
    {
        $base_uri = getenv('SOPA_BASE_URL') ?: 'https://193.208.127.194:83';
        $path = '/NewContract/Contract/EndProcess';
        $args = array_filter($args);

        return add_query_arg($args, $base_uri . $path);
    }
}
