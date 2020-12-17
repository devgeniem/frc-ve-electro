<?php

namespace VE\Electro;

class Plugin
{
    protected $services = [
        Support\Support::class,
        Actions\Actions::class,
        Admin\Acf\AdvancedCustomFields::class,
        Admin\Admin::class,
        CLI\Console::class,
        Models\Product::class,
        Models\ProductGroup::class,
        Models\ProductAdditional::class,
        Http\Rest\CompanyController::class,
    ];

    protected $booted;

    public function bootstrap()
    {
        if ($this->booted) {
            return;
        }

        $this->register();

        $this->booted = true;
    }

    protected function register()
    {
        foreach($this->services as $service) {
            if (method_exists($instance = new $service, 'register')) {
                $instance->register();
            }
        }
    }
}
