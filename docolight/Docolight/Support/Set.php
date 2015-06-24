<?php

namespace Docolight\Support;

use Closure;
use Countable;
use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;

/**
 * Base setter - getter class.
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
class Set implements ArrayAccess, Countable, IteratorAggregate
{
    /**
     * Key-value array of arbitrary data.
     *
     * @var array
     */
    protected $data = array();

    /**
     * Constructor.
     *
     * @param array $items Pre-populate set with this key-value array
     */
    public function __construct($items = array())
    {
        $this->replace($items);
    }

    /**
     * Normalize data key.
     *
     * Used to transform data key into the necessary key format for this set.
     *
     * @param string $key The data key
     *
     * @return mixed The transformed/normalized data key
     */
    protected function normalizeKey($key)
    {
        return $key;
    }

    /**
     * Set data key to value.
     *
     * @param string $key   The data key
     * @param mixed  $value The data value
     */
    public function set($key, $value)
    {
        $this->data[$this->normalizeKey($key)] = $value;
    }

    /**
     * Get data value with key.
     *
     * @param string $key     The data key
     * @param mixed  $default The value to return if data key does not exist
     *
     * @return mixed The data value, or the default value
     */
    public function get($key, $default = null)
    {
        if ($this->has($key)) {
            $isInvokable = is_object($this->data[$this->normalizeKey($key)]) and method_exists($this->data[$this->normalizeKey($key)], '__invoke');

            return $isInvokable ? $this->data[$this->normalizeKey($key)]($this) : $this->data[$this->normalizeKey($key)];
        }

        return $default;
    }

    /**
     * Add data to set.
     *
     * @param array $items Key-value array of data to append to this set
     */
    public function replace($items)
    {
        foreach ($items as $key => $value) {
            $this->set($key, $value); // Ensure keys are normalized
        }
    }

    /**
     * Fetch set data.
     *
     * @return array This set's key-value data array
     */
    public function all()
    {
        return $this->data;
    }

    /**
     * Fetch set data keys.
     *
     * @return array This set's key-value data array keys
     */
    public function keys()
    {
        return array_keys($this->data);
    }

    /**
     * Does this set contain a key?
     *
     * @param string $key The data key
     *
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($this->normalizeKey($key), $this->data);
    }

    /**
     * Remove value with key from this set.
     *
     * @param string $key The data key
     */
    public function remove($key)
    {
        unset($this->data[$this->normalizeKey($key)]);
    }

    /**
     * Get value
     *
     * @param string $offset
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Set value
     *
     * @param string $offset
     * @param mixed  $value
     *
     * @return void
     */
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Determine if value is available.
     *
     * @return bool
     */
    public function __isset($key)
    {
        return $this->has($key);
    }

    /**
     * Remove a value
     *
     * @param string $offset
     *
     * @return void
     */
    public function __unset($key)
    {
        $this->remove($key);
    }

    /**
     * Clear all values.
     *
     * @return void
     */
    public function clear()
    {
        $this->data = array();
    }

    /**
     * Determine if value is available.
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * Get value
     *
     * @param string $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Set value
     *
     * @param string $offset
     * @param mixed  $value
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * Remove a value
     *
     * @param string $offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    /**
     * Countable.
     *
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * IteratorAggregate.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }

    /**
     * Ensure a value or object will remain globally unique.
     *
     * @param string   $key   The value or object name
     * @param \Closure $value The closure that defines the object
     *
     * @return mixed
     */
    public function singleton($key, Closure $value)
    {
        $this->set($key, function ($c) use ($value) {
            static $object;

            if (null === $object) {
                $object = $value($c);
            }

            return $object;
        });
    }

    /**
     * Protect closure from being directly invoked.
     *
     * @param \Closure $callable A closure to keep from being invoked and evaluated
     *
     * @return \Closure
     */
    public function protect(Closure $callable)
    {
        return function () use ($callable) {
            return $callable;
        };
    }
}
