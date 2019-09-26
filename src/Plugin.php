<?php

namespace VE\Electro;

use VE\Electro\Support\Str;
use Carbon\Carbon;

class Plugin
{
    public const NAMESPACE = 'electro';

    public static function boot()
    {
        Carbon::setTestNow(
            Carbon::create('2019-12-01')
        );

        static::load();
        Admin\Admin::load();
    }

    public static function load()
    {
        add_action('init', [__CLASS__, 'actions_init']);
        add_action('cli_init', [__CLASS__, 'cli_init']);

        add_action('init', [__CLASS__, 'models_init']);
    }

    public static function models_init()
    {
        $models = [
            Models\Product::class,
            Models\ProductGroup::class,
        ];

        foreach($models as $model) {
            $model::register();
        }
    }

    public static function actions_init()
    {
        $actionNamespace = static::NAMESPACE;

        $actions = [
            'products',
        ];

        foreach($actions as $actionName) {
            // Get public methods from correspond action class
            $class = __NAMESPACE__ . '\\Actions\\' . Str::studly($actionName);

            $reflection = new \ReflectionClass($class);

            $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
            $methods = array_filter($methods, function($method) {
                return !$method->isStatic();
            });

            foreach($methods as $method) {
                // By default action calls static handle method which finds right method
                // based on action name.
                // Callable as `do_action('electro/action/method', $params);`.
                add_action("{$actionNamespace}/{$actionName}/{$method->name}", [
                    $class, 'handle',
                ]);
            }
        }
    }

    public static function cli_init()
    {
        $commandNamespace = static::NAMESPACE;

        $commands = [
            'debug',
            'products',
        ];

        foreach($commands as $command) {
            $class = __NAMESPACE__ . '\\CLI\\' . Str::studly($command);
            if ( class_exists($class) ) {
                \WP_CLI::add_command("$commandNamespace $command", $class);
            }
        }
    }
}
