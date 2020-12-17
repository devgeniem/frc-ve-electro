<?php

namespace VE\Electro\Admin\Acf;

class ProductGroupFields extends Field
{
    public function register()
    {
        $field_products = [
            'key' => $this->field('ec_products'),
            'name' => 'ec_products',
            'label' => _x('Products', 'acf', 'electro'),
            'type' => 'repeater',
            'layout' => 'block',
            'sub_fields' => [
                [
                    'key' => $this->field('ec_product'),
                    'name' => 'ec_product',

                    'label' => _x('Product', 'acf', 'electro'),
                    'type' => 'post_object',
                    'post_type' => 'ec_product',
                    'multiple' => false,
                    'return_format' => 'id',
                ],
                [
                    'key' => $this->field('consumption_min'),
                    'name' => 'consumption_min',
                    'label' => _x('Consumption Min', 'acf', 'electro'),
                    'type' => 'number',
                    'append' => 'kwh',
                    'wrapper' => [
                        'width' => '50%',
                    ],
                ],
                [
                    'key' => $this->field('consumption_max'),
                    'name' => 'consumption_max',
                    'label' => _x('Consumption Max', 'acf', 'electro'),
                    'type' => 'number',
                    'append' => 'kwh',
                    'wrapper' => [
                        'width' => '50%',
                    ],
                ],
                [
                    'key' => $this->field('product_key'),
                    'name' => 'product_key',
                    'label' => _x('Custom key', 'acf', 'electro'),
                    'type' => 'text',

                    'wrapper' => [
                        'width' => '50%',
                    ],
                ],
                [
                    'key' => $this->field('product_value'),
                    'name' => 'product_value',
                    'label' => _x('Custom value', 'acf', 'electro'),
                    'type' => 'text',
                    'wrapper' => [
                        'width' => '50%',
                    ],
                ],
            ],
            'wrapper' => [
                'width' => '50%',
            ],
        ];

        $field_icon = [
            'key' => $this->field('icon'),
            'name' => 'icon',

            'label' => _x('Icon', 'acf', 'electro'),
            'type' => 'image',
            'return_format' => 'id',
            'wrapper' => [
                'width' => '50%',
            ],
        ];

        $field_description = [
            'key' => $this->field('description'),
            'name' => 'description',

            'label' => _x('Description', 'acf', 'electro'),
            'type' => 'wysiwyg',
            'wrapper' => [
                'width' => '50%',
            ],
            'media_upload' => false,
        ];

        $field_additional_description = [
            'key' => $this->field('additional_description'),
            'name' => 'additional_description',

            'label' => _x('Additional description', 'acf', 'electro'),
            'type' => 'wysiwyg',
            'wrapper' => [
                'width' => '50%',
            ],
        ];

        $field_additional_products = [
            'key' => $this->field('additional_products'),
            'name' => 'additional_products',

            'label' => _x('Additional products', 'acf', 'electro'),
            'type' => 'post_object',
            'post_type' => 'product_additional',
            'multiple' => true,
            'return_format' => 'id',
        ];

        $location = [
            'param' => 'post_type',
            'operator' => '==',
            'value' => 'product_group',
        ];
        $group = [
            'key' => $this->group('product_group'),
            'title' => _x('Product Group', 'acf', 'electro'),
            'fields' => [
                $field_products,
                $field_icon,
                $field_description,
                $field_additional_description,
                $field_additional_products,
            ],
            'location' => [
                [
                    $location,
                ]
            ]
        ];

        acf_add_local_field_group($group);
    }
}
