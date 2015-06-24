<?php

namespace Docolight\Support;

use RuntimeException;
use Docolight\Container\Container;

/**
 * The Facade design pattern is often used when a system is very complex or difficult to understand because the system has a large number of interdependent classes or its source code is unavailable.
 * This pattern hides the complexities of the larger system and provides a simpler interface to the client.
 * It typically involves a single wrapper class which contains a set of members required by client.
 * These members access the system on behalf of the facade client and hide the implementation details.
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
abstract class Facade
{
    /**
     * The application instance being facaded.
     *
     * @var \Docolight\Container\Container
     */
    protected static $container;

    /**
     * The resolved object instances.
     *
     * @var array
     */
    protected static $instances;

    /**
     * Get the root object behind the facade.
     *
     * @return mixed
     */
    public static function root()
    {
        return static::resolve(static::accessor());
    }

    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function accessor()
    {
        throw new RuntimeException('Facade does not implement accessor method.');
    }

    /**
     * Resolve the facade root instance from the container.
     *
     * @param string $name
     *
     * @return mixed
     */
    protected static function resolve($name)
    {
        if (is_object($name)) {
            return $name;
        }

        if (isset(static::$instances[$name])) {
            return static::$instances[$name];
        }

        return static::$instances[$name] = static::$container[$name];
    }

    /**
     * Clear a resolved facade instance.
     *
     * @param string $name
     */
    public static function clear($name)
    {
        unset(static::$instances[$name]);
    }

    /**
     * Clear all of the resolved instances.
     */
    public static function clears()
    {
        static::$instances = array();
    }

    /**
     * Get the application instance behind the facade.
     *
     * @return \Docolight\Container\Container
     *
     * @throws \RuntimeException
     */
    public static function container()
    {
        $container = static::$container;

        if (!$container instanceof Container) {
            throw new RuntimeException('Container not set!');
        }

        return static::$container;
    }

    /**
     * Set the application instance.
     *
     * @param \Docolight\Container\Container $container
     */
    public static function set(Container $container)
    {
        static::$container = $container;
    }

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param string $method
     * @param array  $args
     *
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        $instance = static::root();

        switch (count($args)) {
            case 0:
                return $instance->$method();

            case 1:
                return $instance->$method($args[0]);

            case 2:
                return $instance->$method($args[0], $args[1]);

            case 3:
                return $instance->$method($args[0], $args[1], $args[2]);

            case 4:
                return $instance->$method($args[0], $args[1], $args[2], $args[3]);

            default:
                return call_user_func_array([$instance, $method], $args);
        }
    }
}
