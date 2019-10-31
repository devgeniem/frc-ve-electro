<?php

namespace VE\Electro\Actions;

class Notice
{
    public $action = 'electro/notice';

    protected function add($message, $level = 'info')
    {
        if (defined( 'WP_CLI' ) && WP_CLI) {
            $level = $level == 'info' ? 'log' : $level;
            return \WP_CLI::$level('[Electro] ' . $message);
        }

        if (is_admin()) {
            $userId = get_current_user_id();
            $notices = get_user_option($this->action, $userId) ?? [];
            $notices[] = [$message, $level];
            update_user_option($userId, $this->action, $notices);
        }
    }

    public function displayAdminNotices()
    {
        $userId = get_current_user_id();
        $notices = get_user_option($this->action, $userId);

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

        delete_user_option($userId, $this->action);
    }

    public function info($message) {
        $this->add($message, 'info');
    }

    public function error($message) {
        $this->add($message, 'error');
    }

    public function warning($message) {
        $this->add($message, 'warning');
    }

    public function success($message) {
        $this->add($message, 'success');
    }
}
