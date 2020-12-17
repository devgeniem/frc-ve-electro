<?php

namespace VE\Electro\Admin\ListTable;

class DisplayLastModified
{
    public function register()
    {
        add_action('manage_ec_product_posts_columns', [$this, 'addTitle']);
        add_action('manage_ec_product_posts_custom_column', [$this, 'renderColumn'], 10, 2);
    }

    public function addTitle($columns)
    {
        unset($columns['date']);
        $columns['modified'] = __( 'Last Modified' );
        return $columns;
    }

    public function renderColumn($column, $post_id)
    {
        switch ( $column ) {
            case 'modified':
                $m_orig		= get_post_field( 'post_modified', $post_id, 'raw' );
                $m_stamp	= strtotime( $m_orig );
                $modified	= date('d.m.Y H:i', $m_stamp );

                echo __( 'Last Modified' );
                echo '<br/>';
                echo $modified;
            break;
        }
    }
}
