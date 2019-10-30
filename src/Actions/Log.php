<?php

namespace VE\Electro\Actions;

class Log
{
    public $action = 'electro/log';

    public function handle($message)
    {
        if (defined( 'WP_CLI' ) && WP_CLI) {
            return \WP_CLI::log("[Electro] $message");
        }

        error_log("[Electro] $message");
    }
}
