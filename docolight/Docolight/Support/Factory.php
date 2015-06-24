<?php

namespace Docolight\Support;

use Closure;
use InvalidArgumentException;
use Docolight\Container\Container;

/**
 * In object-oriented programming, a factory is an object for creating other objects – formally a factory is simply an object that returns an object from some method call, which is assumed to be "new". More broadly, a subroutine that returns a "new" object may be referred to as a "factory", as in factory method or factory function. This is a basic concept in OOP, and forms the basis for a number of related software design patterns.
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 *
 * @link https://en.wikipedia.org/wiki/Factory_(object-oriented_programming) Factory (object-oriented programming)
 */
abstract class Factory
{
    /**
     * The container instance.
     *
     * @var
     */
    protected $container;

    /**
     * The registered custom product creators.
     *
     * @var array
     */
    protected $customCreators = [];

    /**
     * The array of created "products".
     *
     * @var array
     */
    protected $products = [];

    /**
     * Create a new manager instance.
     *
     * @param  Container $container
     * @return void
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Get the default product name.
     *
     * @return string
     */
    abstract public function getDefaultProduct();

    /**
     * Get a product instance.
     *
     * @param  string  $product
     * @return mixed
     */
    public function produce($product = null)
    {
        $product = $product ?: $this->getDefaultProduct();
        $identifier = camel_case($product);

        // If the given product has not been created before, we will create the instances
        // here and cache it so we can return it next time very quickly. If there is
        // already a product created by this name, we'll just return that instance.
        if (!isset($this->products[$identifier])) {
            $this->products[$identifier] = $this->createProduct($product);
        }

        return $this->products[$identifier];
    }

    /**
     * Create a new product instance.
     *
     * @param  string  $product
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    protected function createProduct($product)
    {
        $method = $this->getMethod($product);

        // We'll check to see if a creator method exists for the given product. If not we
        // will check for a custom product creator, which allows developers to create
        // products using their own customized product creator Closure to create it.
        if (isset($this->customCreators[$product])) {
            return $this->callCustomCreator($product);
        } elseif (method_exists($this, $method)) {
            return $this->$method();
        }

        throw new InvalidArgumentException("Product [$product] not supported.");
    }

    /**
     * Get method to create product.
     *
     * @param string $product
     *
     * @return string
     */
    protected function getMethod($product)
    {
        return 'create'.ucfirst($product).'Product';
    }

    /**
     * Call a custom product creator.
     *
     * @param  string  $product
     * @return mixed
     */
    protected function callCustomCreator($product)
    {
        $productClosure = $this->customCreators[$product];

        return $productClosure($this->container);
    }

    /**
     * Register a custom product creator Closure.
     *
     * @param  string    $product
     * @param  \Closure  $callback
     * @return $this
     */
    public function extend($product, Closure $callback)
    {
        $this->customCreators[$product] = $callback;

        return $this;
    }

    /**
     * Dynamically call the default product instance.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array(array($this->produce(), $method), $parameters);
    }
}
