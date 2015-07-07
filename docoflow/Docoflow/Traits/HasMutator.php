<?php

namespace Docoflow\Traits;

/**
 * You can attach a static macro method to this class. Means, you can extend the class on the fly.
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
trait HasMutator
{
    /**
     * Stored mutator
     *
     * @var array
     */
    protected static $mutator = [];

    /**
     * Static implementation of this class.
     *
     * @var \Docoflow\Models\WorkflowVerificator
     */
    protected static $instance;

    /**
     * This method is invoked after each record is instantiated by a find method. Here, we can bind static instance for mutator.
     *
     * @return void
     */
    protected function afterFind()
    {
        parent::afterFind();

        static::$instance = $this;
    }

    /**
     * Statically get instance, useful to get instance from mutator
     *
     * @return \Docoflow\Models\WorkflowVerificator
     */
    public static function getInstance()
    {
        return static::$instance;
    }

    /**
     * Determine if mutator is exist
     *
     * @param string $mutator Mutator name
     *
     * @return boolean
     */
    public static function hasMutator($mutator)
    {
        return isset(static::$mutator[$mutator]);
    }

    /**
     * Register new mutator
     *
     * @param string   $mutator  Mutator name
     * @param callable $callback Mutator callback
     *
     * @return void
     */
    public static function mutate($mutator, callable $callback)
    {
        static::$mutator[$mutator] = $callback;
    }

    /**
     * Call mutator statically
     *
     * @param string $method     Mutator name
     * @param array  $parameters Parameters bind to your mutator
     *
     * @return mixed
     */
    public static function callMutator($method, array $parameters = array())
    {
        if (static::$mutator[$method] instanceof Closure) {
            return call_user_func_array(Closure::bind(static::$mutator[$method], null, get_called_class()), $parameters);
        } else {
            return call_user_func_array(static::$mutator[$method], $parameters);
        }
    }
}
