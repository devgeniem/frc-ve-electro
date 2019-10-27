<?php

namespace VE\Electro\Admin\Notices;

class Notices
{
    public function handle()
    {
        $id = 'electro/notices/'. get_current_user_id();
        $notices = get_option($id);

        if (empty($notices)) {
            return;
        }

        foreach($notices as [$message, $level]) {
            $class = sprintf('notice notice-%s', $level);
            echo sprintf(
                '<div class="%1$s"><p>%2$s</p></div>', 
                esc_attr($class), 
                esc_html($message)
            );  
        }

        delete_option($id);
    }
}