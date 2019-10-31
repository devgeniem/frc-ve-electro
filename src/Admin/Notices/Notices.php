<?php

namespace VE\Electro\Admin\Notices;

class Notices
{
    public function handle()
    {
        do_action('electro/notice/displayAdminNotices');
    }
}
