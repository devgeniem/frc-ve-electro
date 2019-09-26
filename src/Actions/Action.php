<?php

namespace VE\Electro\Actions;

class Action
{
    protected static function make()
    {
        return new static();
    }

    public static function handle(...$parameters)
    {
        // Get class method from `electro/action/method` naming convention
        list($namespace, $id, $action) = explode('/', current_action());

        $class = static::make();

        if(method_exists($class, $action)) {
            return $class->$action(...$parameters);
        }
    }
}
