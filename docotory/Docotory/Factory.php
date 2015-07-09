<?php

namespace Docotory;

use ArrayAccess;

/**
 * Base factory class. It can produce many type of a product, you can also bind them on the fly.
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
abstract class Factory implements ArrayAccess
{
    /**
     * Resolved types stack
     *
     * @var array
     */
    protected static $resolved = [];

    /**
     * Registered custom creator
     *
     * @var array
     */
    protected static $customCreator = [];

    /**
     * Register new custom creator
     *
     * @param string   $type
     * @param callable $callable
     *
     * @return void
     */
    public static function extend($type, callable $callable)
    {
        static::$customCreator[$type] = $callable;
    }

    /**
     * Register a custom creator if it has not been registered yet
     *
     * @param string   $type
     * @param callable $callable
     *
     * @return void
     */
    public static function extendIf($type, callable $callable)
    {
        if (! static::hasCustomCreator($type)) {
            static::extend($type, $callable);
        }
    }

    /**
     * Register a new instance of a given type
     *
     * @param string $type
     * @param mixed  $instance
     *
     * @return void
     */
    public static function instance($type, $instance)
    {
        static::$resolved[$type] = $instance;
    }

    /**
     * Determine if a custom creator has been registered or not
     *
     * @param string $type
     *
     * @return boolean
     */
    public static function hasCustomCreator($type)
    {
        return isset(static::$customCreator[$type]);
    }

    /**
     * Determine if a type has been resolved
     *
     * @param string $type
     *
     * @return boolean
     */
    public static function resolved($type)
    {
        return isset(static::$resolved[$type]);
    }

    /**
     * Call custom creator
     *
     * @param string $type
     *
     * @return mixed
     */
    protected static function callCustomCreator($type)
    {
        return call_user_func_array(static::$customCreator[$type], [container()]);
    }

    /**
     * Get type
     *
     * @param string $type
     *
     * @throws ResolvingTypeException
     *
     * @return mixed
     */
    public function __get($type)
    {
        if (static::resolved($type)) {
            return static::$resolved[$type];
        }

        if (static::hasCustomCreator($type)) {
            $instance = static::callCustomCreator($type);
        } else {
            if (!method_exists($this, $methodName = 'create'.ucfirst($type))) {
                throw new ResolvingTypeException("Type [$type] is not supported!");
            }

            $instance = $this->{$methodName}();
        }

        return static::$resolved[$type] = $instance;
    }

    /**
     * Set instance of a type
     *
     * @param string $type
     * @param mixed  $instance
     */
    public function __set($type, $instance)
    {
        static::instance($type, $instance);
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param mixed $type
     *
     * @return bool
     */
    public function offsetExists($type)
    {
        return static::resolved($type);
    }

    /**
     * Get an item at a given offset.
     *
     * @param mixed $type
     *
     * @return mixed
     */
    public function offsetGet($type)
    {
        return static::$resolved[$type];
    }

    /**
     * Set the item at a given offset.
     *
     * @param mixed $type
     * @param mixed $instance
     */
    public function offsetSet($type, $instance)
    {
        static::instance($type, $instance);
    }

    /**
     * Unset the item at a given offset.
     *
     * @param string $type
     */
    public function offsetUnset($type)
    {
        unset(static::$resolved[$type]);
    }
}
