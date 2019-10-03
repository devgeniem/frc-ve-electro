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
            'wrapper' => [
                'width' => '50%',
            ],
        ];

        $field_related_page = [
            'key' => $this->field('related_page'),
            'name' => 'related_page',

            'label' => 'Marketing page',
            'type' => 'link',
        ];

        $field_logo = [
            'key' => $this->field('logo'),
            'name' => 'logo',

            'label' => 'Logo',
            'type' => 'image',
            'return_format' => 'id',
            'wrapper' => [
                'width' => '50%',
            ],
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
            'wrapper' => [
                'width' => '50%',
            ],
        ];

        $field_short_description = [
            'key' => $this->field('description_short'),
            'name' => 'description_short',

            'label' => 'Short description',
            'type' => 'textarea',
            'wrapper' => [
                'width' => '50%',
            ],
        ];

        $field_additional_description = [
            'key' => $this->field('additional_description'),
            'name' => 'additional_description',

            'label' => 'Additional description',
            'type' => 'textarea',
            'wrapper' => [
                'width' => '50%',
            ],
        ];

        $field_short_additional_description = [
            'key' => $this->field('additional_description_short'),
            'name' => 'additional_description_short',

            'label' => 'Short additional description',
            'type' => 'textarea',
            'wrapper' => [
                'width' => '50%',
            ],
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
                $field_logo,
                $field_short_title,
                $field_description,
                $field_additional_description,
                $field_short_description,
                $field_short_additional_description,
                $field_related_page,
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