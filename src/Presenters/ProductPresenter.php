<?php

namespace VE\Electro\Presenters;

use VE\Electro\Product\Enums\ComponentType;
use VE\Electro\Product\Enums\PricePeriodType;
use VE\Electro\Product\Product;
use VE\Electro\Product\ProductPrice;
use VE\Electro\Product\ProductComponent;

class ProductPresenter extends Presenter
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function data(): array
    {
        return $this->product()->toArray();
    }

    protected function product()
    {
        $product = $this->product;

        $components = $this->components($product);

        $periods = collect([PricePeriodType::ACTIVE, PricePeriodType::RELATED])
            ->mapWithKeys(function($period) use ($components) {
                $string = $this->getPeriodString($components, $period);

                if (! $string) {
                    return [];
                }

                $primaryComponents = $components->where('type', ComponentType::PRIMARY);
                $secondaryComponents = $components->where('type', ComponentType::SECONDARY);

                return [
                    $period => collect([
                        'period' => $string,
                        'periodKey' => $this->getPeriodKey($components, $period),
                        'components' => [
                            'primary' => $this->filterPeriod($primaryComponents, $period),
                            'secondary' => $this->filterPeriod($secondaryComponents, $period),
                        ],
                    ]),
                ];
        });

        return collect([
            'id' => $product->id(),
            'title' => $product->title($this->language()),
            'data' => collect([
                'id' => $product->id(),
                'conditions' => collect([
                    'consumptionMax' => $product->meta('consumption_max'),
                    'consumptionMin' => $product->meta('consumption_min'),
                ])->filter(),
                'metering' => $product->getMeasurementMethodId(),
                'type' => $product->type(),
                'prices' => $this->priceValues($components),
                'key' => $product->meta('product_key') ?? 'productCode',
                'value' => $product->meta('product_value') ?? $product->id(),
                'additionalProducts' => $product->type() !== 'additional' && $product->additionalProducts()
                    ? $product->additionalProducts()->map->id()
                    : [],
                'campaignCode' => $product->campaignCode(),
            ])->filter(),
            'meta' => [
                'description' => $product->meta('description'),
                'additional_description' => $product->meta('additional_description'),
                'icon' => $product->meta('icon'),
            ],
            'type' => $product->type(),
            'contractDuration' => $this->translate($product->contractDuration()),
            'displayContractPeriod' => $product->displayContractPeriod(),
            'canHavePriceEstimation' => $product->canHavePriceEstimation(),
            'customerType' => $product->customerType(),
            'periods' => $periods,
            'hasDiscount' => $this->hasDiscount($components) || $product->campaignCode(),
        ])->pipe(function($collection) {
            return $collection->merge([
                'hasRelatedPeriodGroup' => (bool) $collection
                    ->get('periods')->get(PricePeriodType::RELATED),
            ]);
        });
    }

    protected function components(Product $product)
    {
        return $product->components()
            ->map(function(ProductComponent $component) use ($product) {
                return $this->formatComponent($component, $product);
            })->filter(function($component) {
                return ! $component->get('prices')->isEmpty();
            })->sortBy('order');
    }

    protected function formatComponent(
        ProductComponent $component,
        Product $product
    ) {
        return collect([
            'id' => $component->id(),
            'order' => $component->sortOrder(),
            'key' => $component->calculationKey(),
            'type' => $component->type(),
            'description' => $component->description($this->language()),
            'feeType' => $component->feeType(),
            'meta' => $this->translate($component->meta()),
            'prices' => $component->prices()
                ->map(function(ProductPrice $price) use ($product) {
                    return $this->formatPrice($price, $product);
                })
        ]);
    }

    protected function formatPrice(ProductPrice $price, Product $product)
    {
        $price = $product->isForBusiness()
            ? $price->withoutVat()
            : $price->withVat();

        return collect([
            'value' => $price->value(),
            'amount' => $price->amount(),
            'unit' => $price->unit(),
            'hasVat' => $price->hasVat(),
            'hasDiscount' => $price->hasDiscount(),
            'isDiscount' => $price->isDiscount(),
            'campaign' => $price->campaign(),
            'validFrom' => $price->validFrom(),
            'validTo' => $price->validTo(),
            'period' => $price->period(),
            'periodKey' => $price->periodKey(),
        ]);
    }

    protected function priceValues($components)
    {
        return $this->filterPeriod($components, PricePeriodType::ACTIVE)
            ->map(function($component) {
                return collect([
                    'key' => $component->get('key'),
                    'value' => $component->get('prices')
                        ->where('hasDiscount', false)
                        ->where('isDiscount', false)
                        ->first(null, collect([]))
                        ->get('value', 0),
                ]);
            })->values();
    }

    /**
     * Helpers
     */

    protected function filterPeriod($components, $type)
    {
        return $components
            ->map(function($component) use ($type) {
                $prices = $component->get('prices')->where('period', $type);
                return $component->replace(['prices' => $prices]);
            });
    }

    protected function getPeriodString($components, $period)
    {
        $activePeriod = $this->filterPeriod($components, $period)
            ->first();

        if (! $activePeriod) {
            return;
        }

        $activePeriod = $activePeriod->get('prices')
            ->first();

        if (! $activePeriod) {
            return;
        }

        return sprintf(
            '%s - %s',
            $activePeriod['validFrom']->setTimezone('Europe/Helsinki')->format('j.n.'),
            $activePeriod['validTo']->setTimezone('Europe/Helsinki')->format('j.n.Y')
        );
    }

    protected function getPeriodKey($components, $period)
    {
        $activePeriod = $this->filterPeriod($components, $period)
            ->first()
            ->get('prices')
            ->first();

        if (! $activePeriod) {
            return;
        }

        return $activePeriod['periodKey'];
    }

    protected function hasDiscount($components)
    {
        $items = $this->filterPeriod($components, PricePeriodType::ACTIVE);

        return $items
                ->pluck('prices.*.hasDiscount')
                ->flatten()
                ->filter()
                ->isNotEmpty()
            || $items
                ->where('hasDiscount')
                ->isNotEmpty();
    }
}
