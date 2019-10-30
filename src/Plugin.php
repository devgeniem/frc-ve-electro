<?php

namespace VE\Electro;

class Plugin
{
    protected $booted;

    public function boot()
    {
        if ($this->booted) {
            return;
        }

        $this->registerHooks();

        $this->booted = true;
    }

    protected function registerHooks()
    {
        add_action('init', [$this, 'registerActions']);
        add_action('init', [$this, 'registerModels']);
        add_action('init', [$this, 'registerAdmin'], 1);
        add_action('cli_init', [$this, 'registerConsoleCommands']);

        // Add Polylang support
        add_filter('pll_get_post_types', function($post_types, $is_settings ) {
            if ( $is_settings ) {
                unset( $post_types['product_group'] );
            } else {
                $post_types['product_group'] = 'product_group';
            }
            return $post_types;
        }, 10, 2);

    }

    public function registerAdmin()
    {
        if (! is_admin()) {
            return;
        }

        (new Admin\Admin)->registerHooks();
    }

    public function registerModels()
    {
        $models = [
            Models\Product::class,
            Models\ProductGroup::class,
        ];

        foreach($models as $model) {
            $model::register();
        }
    }

    public function registerActions()
    {
        $actions = [
            Actions\Log::class,
            Actions\Notice::class,
            Actions\Products::class,
        ];

        foreach($actions as $class) {
            // Get public methods from correspond action class
            $reflection = new \ReflectionClass($class);

            $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
            $methods = array_filter($methods, function($method) {
                return !$method->isStatic();
            });

            $instance = new $class;

            foreach($methods as $method) {
                // By default action calls static handle method which finds right method
                // based on action name.
                // Callable as `do_action('electro/action/method', $params);`.
                $action = "{$instance->action}/{$method->name}";
                if ($method->name === 'handle') {
                    $action = $instance->action;
                }

                add_action($action, [$instance, $method->name]);
            }
        }
    }

    public function registerConsoleCommands()
    {
        $commands = [
            CLI\Products::class,
        ];

        foreach($commands as $command) {
            $instance = new $command;
            \WP_CLI::add_command($instance->command, $instance);
        }
    }
}
