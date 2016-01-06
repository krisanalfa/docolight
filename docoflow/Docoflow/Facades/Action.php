<?php

namespace Docoflow\Facades;

use Docolight\Support\Facade;
use Docoflow\Models\WorkflowAction;

class Action extends Facade
{
    protected static function accessor()
    {
        static::$container->bindIf('docoflow.models.Action', function ($container) {
            return new WorkflowAction('search');
        });

        return static::$container->make('docoflow.models.Action');
    }
}
