<?php

namespace VE\Electro\Admin\Acf;

class AdvancedCustomFields
{
    protected $fields = [
        ProductGroupFields::class,
        ProductAdditionalFields::class,
    ];

    public function register()
    {
        add_action('acf/init', [$this, 'registerFields']);
    }

    public function registerFields()
    {
        foreach($this->fields as $field) {
            (new $field)->register();
        }
    }
}
