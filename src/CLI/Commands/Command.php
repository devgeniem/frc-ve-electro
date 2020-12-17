<?php

namespace VE\Electro\CLI\Commands;

abstract class Command
{
    protected $command;

    public function command()
    {
        return $this->command;
    }
}
