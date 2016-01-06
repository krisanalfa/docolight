<?php

namespace Docoflow\Facades;

use Docolight\Support\Facade;
use Docoflow\Models\WorkflowActivity;

class Activity extends Facade
{
    protected static function accessor()
    {
        static::$container->bindIf('docoflow.models.Activity', function ($container) {
            return new WorkflowActivity('search');
        });

        return static::$container->make('docoflow.models.Activity');
    }
}
