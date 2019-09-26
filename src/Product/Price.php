<?php

namespace VE\Electro\Product;

use VE\Electro\Money;
use VE\Electro\Vat\VatRepository;
use NumberFormatter;

class Price
{
    protected $money;
    protected $vat;
    protected $withVat;
    protected $formatted;
    protected $perUnit;

    public function __construct(array $args = [])
    {
        $defaults = [
            'amount' => 0,
            'vat' => 'zero',
            'withVat' => true,
            'currency' => 'EUR',
            'decimals' => 2,
            'perUnit' => null,
        ];

        $args = wp_parse_args($args, $defaults);

        $this->money = new Money\Money(
            $args['amount'],
            new Money\Currency($args['currency'])
        );

        $this->vat = VatRepository::get($args['vat']);
        $this->withVat = $args['withVat'];
        $this->perUnit = $args['perUnit'];

        $numberFormatter = new NumberFormatter('fi_FI', NumberFormatter::DECIMAL);
        $numberFormatter->setAttribute(
            NumberFormatter::FRACTION_DIGITS,
            $args['decimals']
        );

        $this->formatter = $numberFormatter;
    }

    public function withVat()
    {
        $this->withVat = true;
        return $this;
    }

    public function withoutVat()
    {
        $this->withVat = false;
        return $this;
    }

    public function output()
    {
        // @TODO: maybe move logic
        return sprintf(
            '%s %s%s%s',
            $this->getAmount(),
            $this->getCurrencySymbol(),
            $this->getPerDelimiter(),
            $this->getPerUnit()
        );
    }

    public function format($money)
    {
        $amount = $money->getAmount();
        return $this->formatter->format($amount);
    }

    public function getAmount()
    {
        $money = $this->money;

        if ( $this->withVat ) {
            $money = $this->vat->addTo($money);
        }

        return $this->format($money);
    }

    public function getCurrencySymbol()
    {
       return $this->money->getCurrency()->getSymbol();
    }

    public function getPerDelimiter()
    {
        return '/';
    }

    public function getPerUnit()
    {
        return $this->perUnit;
    }

    public function __toString()
    {
        return $this->output();
    }
}
