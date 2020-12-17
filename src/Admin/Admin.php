<?php

namespace VE\Electro\Admin;

class Admin
{
    protected $services = [
        ListTable\DisplayLastModified::class,
        AdminBar\SyncEnerimProducts::class,
        Actions\EnerimSyncProducts::class,
        Actions\EnerimJsonUpload::class,
        Notices\Notices::class,
        AdminMenu\UploadJson::class,
        AdminMenu\SopaLinkGenerator::class,
        MetaBox\ForEcProduct::class,
    ];

    public function register()
    {
        if (! is_admin()) {
            return;
        }

        foreach($this->services as $service) {
            if (method_exists($instance = new $service, 'register')) {
                $instance->register();
            }
        }
    }
}
