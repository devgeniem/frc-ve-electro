<?php

namespace VE\Electro\Admin\ListTable;

class AddLastModified
{
    public function handle($columns)
    {
        unset($columns['date']);
        $columns['modified'] = __( 'Last Modified' );
        return $columns;
    }
}
