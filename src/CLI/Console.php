<?php

namespace VE\Electro\CLI;

use WP_CLI;

class Console
{
    protected $commands = [
        Commands\Products::class,
    ];

    public function register()
    {
        add_action('cli_init', [$this, 'registerCommands']);
    }

    public function registerCommands()
    {
        foreach($this->commands as $command) {
            $instance = new $command;
            WP_CLI::add_command($instance->command(), $instance);
        }
    }
}
