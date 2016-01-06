<?php

namespace Docoflow\Facades;

use Docolight\Support\Facade;
use Docoflow\Flo as RealFlo;

class Flo extends Facade
{
    protected static function accessor()
    {
        static::$container->bindIf('docoflow.flo', function ($container) {
            return new RealFlo();
        });

        return static::$container->make('docoflow.flo');
    }
}
