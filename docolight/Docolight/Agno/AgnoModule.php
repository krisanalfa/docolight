<?php

namespace Docolight\Agno;

use Yii;
use CWebModule;
use Docotory\Traits\HasFactories;
use Docolight\Agno\Traits\HasAutoload;
use Docolight\Agno\Traits\HasAssetsUrl;

/**
 * Make your module can be accessible via facade method.
 *
 * @todo Call static could not be implemented yet, because this class is behind the facade.
 */
abstract class AgnoModule extends CWebModule
{
    use HasFactories, HasAssetsUrl, HasAutoload;

    /**
     * Available factories
     *
     * @var array
     */
    public $availableFactories = [];

    /**
     * Interface / Alias / Class bindings
     *
     * @var array
     */
    public $bindings = [];

    /**
     * If you override init method in your module, make sure you called parent::init() to make the Facade itself be accessible.
     */
    public function init()
    {
        parent::init();

        $this->loadPsr();
        $this->loadAutoload();
        $this->registerBindings();
        $this->registerFacadeAccessor();
        $this->registerAvailableFactories();
    }

    /**
     * Register all bindings
     *
     * @return void
     */
    public function registerBindings()
    {
        foreach ($this->bindings as $binding) {
            list($definition, $concrete) = $binding;

            Yii::app()->container->singleton($definition, $concrete);
        }
    }

    /**
     * Register your facade accessor.
     */
    protected function registerFacadeAccessor()
    {
        // Hack API facade, so any components / controllers / models / views / child modules
        // on this module can access the facade too
        $that = $this;

        Yii::app()->container->singleton($this->getFacadeAccessor(), function ($container) use ($that) {
            return $that;
        });
        // End of facade hack
    }

    /**
     * Register available factories
     */
    protected function registerAvailableFactories()
    {
        if ($this->availableFactories) {
            // Factory singleton
            foreach ($this->availableFactories as $alias => $factory) {
                Yii::app()->container->singleton($alias, $factory);
            }

            // Inject factories to this implementation
            $this->registerFactories($this->availableFactories);
        }
    }

    /**
     * Unique facade accessor
     *
     * @return string
     */
    protected function getFacadeAccessor()
    {
        return $this->getId();
    }

    /**
     * If an attribute of this class called
     *
     * @param string $type
     *
     * @return mixed
     */
    public function __get($type)
    {
        if ($this->hasFactory($type)) {
            return $this->factory($type);
        }

        return parent::__get($type);
    }

    /**
     * Handle method not found. Let's search in base factory first.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($name, $parameters)
    {
        if ($this->hasFactory('base')) {
            $base = $this->factory('base');

            if (method_exists($base, $name)) {
                return call_user_func_array([$base, $name], $parameters);
            }
        }
    }
}
