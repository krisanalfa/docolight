<?php

namespace Docoflow\Facades;

use Docolight\Support\Facade;
use Docoflow\Models\WorkflowStateActivity;

class StateActivity extends Facade
{
    protected static function accessor()
    {
        static::$container->bindIf('docoflow.models.StateActivity', function ($container) {
            return new WorkflowStateActivity('search');
        });

        return static::$container->make('docoflow.models.StateActivity');
    }
}
