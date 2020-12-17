<?php

namespace VE\Electro\Actions;

class Actions
{
    protected $actions = [
        Log::class,
        Notice::class,
        Products::class,
    ];

    public function register()
    {
        add_action('init', [$this, 'registerActions']);
    }

    public function registerActions()
    {
        foreach($this->actions as $class) {
            // Get public methods from correspond action class
            $reflection = new \ReflectionClass($class);

            $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
            $methods = array_filter($methods, function($method) {
                return ! $method->isStatic();
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
}
