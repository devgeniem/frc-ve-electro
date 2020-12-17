<?php

namespace VE\Electro\Product;

use Illuminate\Support\Str;
use VE\Electro\Product\Collections\PayloadCollection;
use VE\Electro\Product\Enums\FeeType;

class ProductComponent
{
    protected $payload;

    protected $product;

    public function __construct(PayloadCollection $payload, Product $product)
    {
        $this->payload = $payload;

        $this->product = $product;
    }

    public function id()
    {
        return $this->payload->get('component_name');
    }

    public function description($lang = true)
    {
        return $this->payload->get('product_component_descriptions')
            ->where('language', $lang)
            ->first()
            ->get('description');
    }

    public function calculationKey()
    {
        return $this->payload->get('time_period_model_key') ?? 'month';
    }

    public function sortOrder()
    {
        return $this->payload->get('sort_order');
    }

    public function type()
    {
        return $this->product->componentType($this);
    }

    public function feeType()
    {
        if (Str::endsWith($this->id(), '-PM')) {
            return FeeType::MONTHLY;
        }

        if (Str::endsWith($this->id(), '-KM')) {
            return FeeType::MONTHLY;
        }

        if (Str::endsWith($this->id(), '-MG')) {
            return FeeType::MARGINAL;
        }

        if (Str::endsWith($this->id(), '-KA')) {
            return FeeType::DISCOUNT;
        }

        return FeeType::ENERGY;
    }

    public function isFeeType($type)
    {
        return $this->feeType() === $type;
    }

    public function isDiscount()
    {
        return $this->isFeeType(FeeType::DISCOUNT);
    }

    public function prices()
    {
        return $this->payload->get('component_prices')
            ->map(function($payload) {
                return new ProductPrice($payload, $this, $this->product);
            });
    }

    public function tariffId()
    {
        return $this->payload->get('tariff_id');
    }

    public function priceDecimals()
    {
        return $this->payload->get('price_decimals');
    }

    public function meta()
    {
        return $this->product->componentMeta($this);
    }
}
