# EnerimCIS API

## API

See document `EnerimCIS_Extranet_palvelukuvaus.pdf` for description of the API.

Pages 76-78, 173-177, 188- are related to Products.

## HTTP Client

The client is in `src/EnerimcCIS/Http/Client.php`. To help constructing
the Client, factory class is in `src/EnerimCIS/EnerimAPI.php`. EnerimAPI
class also includes the resource call methods.

Usage example:

```php
$api = EnerimAPI::factory(); // Create HTTP client
$products = $api->products(); // Fetches all products from the API
...
```

## Middlewares

If it is required to use Proxy or client authentication those can be applied
via middlewares. 

There are two middlewares ready to use:

    - `src/EnerimCIS/Http/Middleware/Auth.php`
    - `src/EnerimCIS/Http/Middleware/Proxy.php`

Use `Enerim::factory` to use theme as:

```php
public static function factory()
{
    return new static(
        env('ENERIM_API_KEY'),
        env('ENERIM_API_PARTY_ID'),
        [
            'middlewares' => [
                new Http\Middleware\Auth(env('{KEY}'), env('{CERT}')),
            ]
        ]
    );
}
```

Creating new middlewares requires to have `handle` method which is applied
before HTTP call. Optional `tearDown` method is called after HTTP call.

## Response

Example of raw JSON response:

```json
{
    "product_name": "JATKUVAPE1",
    "valid_from": "2020-03-31T21:00:00Z",
    "valid_to": "9999-12-31T23:59:59Z",
    "product_type": 90001,
    "inheritance": 142002,
    "public_pricing": 142001,
    "product_account_group": 0,
    "accounting_item_6": "1200",
    "contract_validity": 155001,
    "contract_validity_duration": 0,
    "contract_validity_duration_unit": 0,
    "contract_conditional_end_date": "0001-01-01T00:00:00",
    "additional_information": null,
    "contract_clause": null,
    "bonus_product": 0,
    "named_price_serie": null,
    "followup_offer_product_name_1": null,
    "followup_offer_product_name_2": null,
    "followup_offer_product_name_3": null,
    "followup_offer_product_name_4": null,
    "followup_offer_product_name_5": null,
    "default_product_name": null,
    "pricing_method": "Hinnasto",
    "protection_method": "Puolivuotistuote",
    "product_group": null,
    "campaign_discount": 0,
    "product_components": [
        {
            "component_name": "JATKUVAPE1-PM",
            "billable_default": 142001,
            "sort_order": 1,
            "price_decimals": 135003,
            "time_period_model_key": null,
            "calendar_key": "Virallinen",
            "component_accounting_group": 60001,
            "invoicing_formula_key": null,
            "accounting_item_8": "TUO3",
            "tariff_id": 144002,
            "component_prices": [
                {
                    "vat_rate": 78001,
                    "valid_from": "2020-03-31T21:00:00Z",
                    "valid_to": "9999-12-31T23:59:59Z",
                    "currency": 5001,
                    "unit_price": 3.0645,
                    "price_valid": 155001,
                    "price_valid_period": 0,
                    "price_valid_period_unit": 0,
                    "price_valid_type": 156001,
                    "price_valid_duration": 0,
                    "price_valid_duration_unit": 0,
                    "formula_type_key": null,
                    "enrgy_profit": 0
                }
            ],
            "product_component_descriptions": [
                {
                    "language": 32001,
                    "description": "Perusmaksu"
                },
                {
                    "language": 32002,
                    "description": "Basic fee"
                },
                {
                    "language": 32004,
                    "description": "Grundavgift"
                }
            ]
        },
        {
            "component_name": "JATKUVAPE1-E",
            "billable_default": 142001,
            "sort_order": 2,
            "price_decimals": 135003,
            "time_period_model_key": "YLEIS",
            "calendar_key": "Virallinen",
            "component_accounting_group": 60001,
            "invoicing_formula_key": null,
            "accounting_item_8": "TUO3",
            "tariff_id": 144001,
            "component_prices": [
                {
                    "vat_rate": 78001,
                    "valid_from": "2020-03-31T21:00:00Z",
                    "valid_to": "9999-12-31T23:59:59Z",
                    "currency": 5002,
                    "unit_price": 5.3065,
                    "price_valid": 155001,
                    "price_valid_period": 0,
                    "price_valid_period_unit": 0,
                    "price_valid_type": 156001,
                    "price_valid_duration": 0,
                    "price_valid_duration_unit": 0,
                    "formula_type_key": null,
                    "enrgy_profit": 0
                }
            ],
            "product_component_descriptions": [
                {
                    "language": 32001,
                    "description": "Energia"
                },
                {
                    "language": 32002,
                    "description": "Energy"
                },
                {
                    "language": 32004,
                    "description": "Energi"
                }
            ]
        }
    ],
    "product_descriptions": [
        {
            "language": 32001,
            "description": "Oomi Jatkuva",
            "additional_information": null,
            "contract_clause": null
        },
        {
            "language": 32002,
            "description": "Oomi Nonstop",
            "additional_information": null,
            "contract_clause": null
        },
        {
            "language": 32004,
            "description": "Oomi Konstant",
            "additional_information": null,
            "contract_clause": null
        }
    ],
    "product_dynamic_properties": [
        {
            "dynamic_property_name": "CustomerType",
            "dynamic_property_value": [
                "Henkil\u00f6"
            ]
        },
        {
            "dynamic_property_name": "ContractType",
            "dynamic_property_value": [
                "S\u00e4hk\u00f6nmyyntisopimus"
            ]
        },
        {
            "dynamic_property_name": "Asiakasryhmittely",
            "dynamic_property_value": [
                "Kuluttaja"
            ]
        },
        {
            "dynamic_property_name": "ContractChannel",
            "dynamic_property_value": [
                "Asiakaspalvelu",
                "Help desk",
                "Puhelinmyynti",
                "Promootiomyynti f2f",
                "Verkkosivut",
                "Online",
                "S\u00e4hk\u00f6nhinta.fi",
                "VE admin"
            ]
        },
        {
            "dynamic_property_name": "ContractTermType",
            "dynamic_property_value": [
                "Toistaiseksi"
            ]
        },
        {
            "dynamic_property_name": "MeasurementMethod",
            "dynamic_property_value": [
                "1 = Yksiaikamittaus"
            ]
        }
    ]
}
```

## Breakdown of key attributes

### Product

`root` referenced as "product".

### Product components

`root.product_components` referenced as "product component".

Product can have multiple `product_components`.

### Component prices / price periods

`root.product_components.component_prices` "component price" or "price periods".

Product component can have multiple price periods. Prices requiers data from
parent object `product_components`.

#### Campaigns

`root.product_components.component_prices` has properties

```json
{
    "price_valid_period": 0,
    "price_valid_period_unit": 0,
    "price_valid_type": 156001,
    "price_valid_duration": 0,
    "price_valid_duration_unit": 0,
}
```

which determines if product has a campaign.
