<?php
namespace VE\Electro\Admin\Acf;

class ProductGroupFields extends Field
{    
    public function register()
    {
        
        $field_products = [
            'key' => $this->field('ec_products'),
            'name' => 'ec_products',

            'label' => 'Products',
            'type' => 'post_object',
            'post_type' => 'ec_product',
            'multiple' => true,
            'return_format' => 'id',
        ];

        $field_title = [
            'key' => $this->field('title'),
            'name' => 'title',

            'label' => 'Title',
            'type' => 'text',
        ];

        $field_short_title = [
            'key' => $this->field('title_short'),
            'name' => 'title_short',

            'label' => 'Short Title',
            'type' => 'text',
        ];

        $field_description = [
            'key' => $this->field('description'),
            'name' => 'description',

            'label' => 'Description',
            'type' => 'textarea',
        ];

        $field_short_description = [
            'key' => $this->field('description_short'),
            'name' => 'description_short',

            'label' => 'Short Description',
            'type' => 'textarea',
        ];

        $location = [
            'param' => 'post_type',
            'operator' => '==',
            'value' => 'product_group',
        ];
        $group = [
            'key' => $this->group('product_group'),
            'title' => 'Product Group',
            'fields' => [
                $field_products,
                $field_title,
                $field_short_title,
                $field_description,
                $field_short_description,
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