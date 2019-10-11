<?php

namespace VE\Electro\Admin\ListTable;

class DisplayLastModified
{
    public function handle($column, $post_id)
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
