<?php

namespace VE\Electro\Product;

use ReflectionClass;
use VE\Electro\Support\Str;
use VE\Electro\EnerimCIS\Enums\Code;

class Factory
{
    protected $payload;

    protected $variants = [
        Variants\Package::class,
        Variants\Temporary::class,
        Variants\Tariff::class,
        Variants\Spot::class,
    ];

    protected $default = Variants\Standard::class;

    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    public function output()
    {
        foreach($this->variants as $variant) {
            $className = (new ReflectionClass($variant))->getShortName();
            $methodName = "is$className";

            if (
                method_exists($this, $methodName)
                && $this->$methodName()
            ) {
                return new $variant($this->payload);
            }
        }

        return new $this->default($this->payload);
    }

    public function isSpot()
    {
        return $this->payload->get('product_components')
            ->filter(function($component) {
                return Str::endsWith($component['component_name'], '-MG');
            })->isNotEmpty();
    }

    public function isPackage()
    {
        return $this->payload->get('product_group') === Code::GROUP_PACKAGE;
    }

    public function isTemporary()
    {
        return $this->payload->get('protection_method') === Code::TYPE_TEMPORARY;
    }

    public function isTariff()
    {
        return $this->payload->get('protection_method') === Code::TYPE_TARIFF;
    }

    public static function product($payload)
    {
        return (new static($payload))->output();
    }
}
