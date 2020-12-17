<?php

namespace VE\Electro\Admin\Notices;

class Notices
{
    public function register()
    {
        add_action('admin_notices', [$this, 'handle']);
    }

    public function handle()
    {
        do_action('electro/notice/displayAdminNotices');
    }
}
