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

        $id = 'electro/notices/'. get_current_user_id();
        $notices = get_option($id) ?? [];
        $notices[] = [$message, $level];
        update_option($id, $notices);
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