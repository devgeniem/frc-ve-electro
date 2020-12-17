<?php

namespace VE\Electro\Product;

use VE\Electro\Electro;
use VE\Electro\EnerimCIS\Enums\Code;
use VE\Electro\Money\Currency;
use VE\Electro\Money\Money;
use VE\Electro\Product\Collections\PayloadCollection;
use VE\Electro\Product\Formatters\PriceFormatter;
use VE\Electro\Vat\VatRepository;

class ProductPrice
{
    protected $payload;

    protected $component;

    protected $product;

    protected $money;

    protected $moneyVatless;

    protected $moneyVat;

    protected $hasVat = false;

    protected $formatter;

    public function __construct(
        PayloadCollection $payload,
        ProductComponent $component,
        Product $product
    ) {
        $this->payload = $payload;

        $this->component = $component;

        $this->product = $product;

        $this->currency = new Currency($this->payload->get('currency'));
        $vat = VatRepository::get($this->payload->get('vat_rate'));

        $this->formatter = (new PriceFormatter())->decimals(
            $this->component->priceDecimals()
        );

        $this->money = new Money(
            $this->payload->get('unit_price'),
            $this->currency
        );

        $this->moneyVatless = $this->money;
        $this->moneyVat = $vat->addTo($this->money);

        $this->money = $this->moneyVat;

    }

    public function getPerUnit()
    {
        if ($this->isDiscount()) {
            return;
        }

        if ($this->component->tariffId() === Code::TARIFF_TIME_CRITICAL) {
            return Electro::translate('UNIT.MONTH');
        }

        return Electro::translate('UNIT.KWH');
    }

    public function withVat()
    {
        $this->money = $this->moneyVat;
        $this->hasVat = true;
        return $this;
    }

    public function withoutVat()
    {
        $this->money = $this->moneyVatless;
        $this->hasVat = false;
        return $this;
    }

    public function hasVat()
    {
        return $this->hasVat;
    }

    public function value()
    {
        if ($this->currency->isSubunit()) {
            return round(
                $this->money->getValue(),
                $this->component->priceDecimals() + 2
            );
        }
        return round(
            $this->money->getValue(),
            $this->component->priceDecimals()
        );
    }

    public function amount()
    {
        return $this->formatter->format(
            $this->money->getAmount()
        );
    }

    public function currencySymbol()
    {
        return $this->currency->getSymbol();
    }

    public function unit()
    {
        if (! $this->getPerUnit()) {
            return $this->currencySymbol();
        }

        return sprintf(
            '%s/%s',
            $this->currencySymbol(),
            $this->getPerUnit()
        );
    }

    public function hasDiscount()
    {
        return (bool) $this->payload->get('price_valid_duration');
    }

    public function isDiscount()
    {
        return $this->component->isDiscount();
    }

    public function validFrom()
    {
        return $this->product->filterPricePeriodFrom(
            $this->payload->get('valid_from')
        );
    }

    public function validTo()
    {
        return $this->product->filterPricePeriodTo(
            $this->payload->get('valid_to'),
            $this->validFrom()
        );
    }

    public function period()
    {
        return $this->product->pricePeriod(
            $this->validFrom(),
            $this->validTo()
        );
    }

    public function periodKey()
    {
        return $this->product->pricePeriodKey(
            $this->validFrom(),
            $this->validTo()
        );
    }

    public function campaign()
    {
        return [
            'duration' => $this->payload->get('price_valid_duration'),
            'durationUnit' => Electro::translate(
                $this->payload->get('price_valid_duration_unit')
            ),
        ];
    }
}
