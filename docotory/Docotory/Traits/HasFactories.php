<?php

namespace Docotory\Traits;

use InvalidArgumentException;

/**
 * A helper trait to make your implementation has a factory.
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
trait HasFactories
{
    /**
     * A key / value pair array of your registered factories
     *
     * @var array
     */
    protected $factories = [];

    /**
     * A key / value pair array of your resolved factories
     *
     * @var array
     */
    protected $resolvedFactories = [];

    /**
     * Resolve a factory. Keep in mind that factory would be resolved via container method.
     * So make sure if the accessor is an alias, register it on a container by calling:
     *
     * ```php
     * container()->alias('FooClass', 'foo');
     * ```
     *
     * @param string $factory Name of your registered factory
     *
     * @return mixed
     */
    public function factory($factory)
    {
        if ($this->hasFactory($factory)) {
            if (! $this->factoryHasBeenResolved($factory)) {
                $this->resolvedFactories[$factory] = container($this->factories[$factory]);
            }

            return $this->resolvedFactories[$factory];
        }
    }

    /**
     * Determine if a factory has been resolved
     *
     * @param string $factory Factory name
     *
     * @return bool
     */
    protected function factoryHasBeenResolved($factory)
    {
        return isset($this->resolvedFactories[$factory]);
    }

    /**
     * Determine if your factory has been registered or not.
     *
     * @param string $factory Name of your factory
     *
     * @return boolean
     */
    public function hasFactory($factory)
    {
        return isset($this->factories[$factory]);
    }

    /**
     * Register your factory
     *
     * @param string  $factory
     * @param string  $containerAccessor
     * @param boolean $replace
     *
     * @return void
     */
    public function registerFactory($factory, $containerAccessor, $replace = false)
    {
        if (! $factory or ! $containerAccessor) {
            throw new InvalidArgumentException("Cannot register factory.");
        }

        // Don't replace
        if (! $replace) {
            // Don't replace if exist
            if ($this->hasFactory($factory)) {
                return;
            }
        }
        // If replace, unset the resolved one
        else {
            if ($this->factoryHasBeenResolved($factory)) {
                unset($this->resolvedFactories[$factory]);
            }
        }

        $this->factories[$factory] = $containerAccessor;
    }

    /**
     * Register your factory from key / value pair array
     *
     * @param array   $factories
     * @param boolean $replace
     *
     * @return void
     */
    public function registerFactories(array $factories, $replace = false)
    {
        foreach ($factories as $factory => $containerAccessor) {
            $this->registerFactory($factory, $containerAccessor, $replace);
        }
    }

    /**
     * Return all registered factories.
     *
     * @return array
     */
    public function factories()
    {
        return $this->factories;
    }
}
