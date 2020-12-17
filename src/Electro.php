<?php

namespace VE\Electro;

use VE\Electro\EnerimCIS\Enums\Code;

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
            'UNIT.MONTH' => [
                'fi' => 'kk',
                'en' => 'month',
                'sv' => 'mån',
            ],
            'UNIT.KWH' => [
                'fi' => 'kWh',
                'en' => 'kWh',
                'sv' => 'kWh',
            ],
            'temporary' => [
                'fi' => 'määräaikainen',
                'en' => 'fixed-term',
                'sv' => 'visstids',
            ],
            'permanent' => [
                'fi' => 'voimassa toistaiseksi',
                'en' => 'until further notice',
                'sv' => 'tillsvidare',
            ],
            'METERING.COMMON' => [
                'fi' => 'Yleissähkö',
                'en' => 'Common',
                'sv' => 'Allmän el',
            ],
            'METERING.TIME' => [
                'fi' => 'Aikasähkö',
                'en' => 'Time',
                'sv' => 'Tidsel',
            ],
            'METERING.SEASON' => [
                'fi' => 'Kausisähkö',
                'en' => 'Season',
                'sv' => 'Säsongel',
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
            'COMPONENTS.SPOT.META' => [
                'fi' => '+  Vaihtuva Spot-tuntihinta',
                'en' => '+ Spot price',
                'sv' => '+ Spot priset',
            ],
            'from' => [
                'fi' => '%s alkaen',
                'en' => 'from %s',
                'sv' => 'från %s',
            ],
            'CONSUMPTION.COMMON' => [
                'fi' => 'Energia',
                'en' => 'Energy',
                'sv' => 'Energi',
            ],
            'CONSUMPTION.TIME_DAY' => [
                'fi' => 'Päiväenergia',
                'en' => 'Day Energy',
                'sv' => 'Dag energi',
            ],
            'CONSUMPTION.TIME_NIGHT' => [
                'fi' => 'Yöenergia',
                'en' => 'Night Energy',
                'sv' => 'Natt energi',
            ],
            'CONSUMPTION.SEASON_WINTER' => [
                'fi' => 'Talvipäiväenergia',
                'en' => 'Winter day energy',
                'sv' => 'Vinterdag energi',
            ],
            'CONSUMPTION.SEASON_OTHER' => [
                'fi' => 'Muun ajan energia',
                'en' => 'Other Time energy',
                'sv' => 'Annan tidsenergi',
            ],
            'HOUSE_TYPE.APARTMENT' => [
                'fi' => 'Kerrostalo',
                'en' => 'Apartment',
                'sv' => 'Flervaningshus',
            ],
            'HOUSE_TYPE.ROW_HOUSE' => [
                'fi' => 'Pari- tai rivitalo',
                'en' => 'Row house',
                'sv' => 'Radhus',
            ],
            'HOUSE_TYPE.TOWN_HOUSE' => [
                'fi' => 'Omakotitalo',
                'en' => 'Town house',
                'sv' => 'Enfamiljshus',
            ],
            'HOUSE_TYPE.OTHER' => [
                'fi' => 'Muu asunto',
                'en' => 'Other house',
                'sv' => 'Annan hus',
            ],
        ];

        return $translations[$key][$locale] ?? $key;
    }

    public static function sopaLink($args = [])
    {
        $base_uri = getenv('SOPA_BASE_URL') ?: 'https://online.oomi.fi';
        $path = '/NewContract/Contract/EndProcess';
        $args = array_filter($args);

        return add_query_arg($args, $base_uri . $path);
    }
}
