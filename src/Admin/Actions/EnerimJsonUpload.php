<?php

namespace VE\Electro\Admin\Actions;

class EnerimJsonUpload
{
    public function handle()
    {
        check_admin_referer('enerim_json_upload');

        $data = [];

        if (isset($_FILES['file'])) {
            $data = file_get_contents(
                $_FILES['file']['tmp_name']
            );
            $data = json_decode($data, true);
        }

        if(empty($data[0]['product_name'])) {
            $data = null;

            do_action(
                'electro/notice/error',
                'Import failed: file is not valid.',
            );
        }


        if ($data) {
            do_action('electro/products/save', $data);

            do_action(
                'electro/notice/success',
                'Imported products from JSON.'
            );
        }

        wp_safe_redirect(wp_get_referer());
    }

}
