<?php

namespace Docolight\Support;

use ArrayAccess;
use CActiveRecord;
use JsonSerializable;
use Docolight\Http\Contracts\Arrayable;

/**
 * Magic class like a model.
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
class Fluent implements ArrayAccess, JsonSerializable, Arrayable
{
    /**
     * Data Attribute in this class.
     *
     * @var array
     */
    protected $attributes = array();

    /**
     * Use this to convert attributes to json.
     *
     * @var array
     */
    protected $jsonAble = null;

    /**
     * Initialize the class.
     *
     * @param array $default Default attribute inside this class
     */
    public function __construct(array $default = array())
    {
        $this->fill($default);
    }

    /**
     * Initialize the class statically.
     *
     * @param array $default Default attribute inside this class
     */
    public static function make(array $default = array())
    {
        return new static($default);
    }

    /**
     * Determine if index is exist in attributes.
     *
     * @param string $index
     *
     * @return bool
     */
    public function has($index)
    {
        return isset($this->attributes[$index]);
    }

    /**
     * Remove an attributes.
     *
     * @param string $index
     */
    public function remove($index)
    {
        if ($this->has($index)) {
            unset($this->attributes[$index]);
        }
    }

    /**
     * Set an attribute value.
     *
     * @param string $index
     * @param mixed  $value
     */
    public function set($index, $value)
    {
        $this->attributes[$index] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function fill($attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Bulk remove attributes by an array contains indexes name you want to remove.
     *
     * @param array $attributes
     *
     * @return
     */
    public function clear(array $attributes)
    {
        foreach ($attributes as $index) {
            $this->remove($index);
        }
    }

    /**
     * Get a list of array which only if the key is exists on the given argument.
     *
     * @param array $attributes List of array key you want to get from your attributes.
     *
     * @see Arr::only()
     *
     * @return array
     */
    public function only(array $attributes)
    {
        return Arr::only($this->attributes, $attributes);
    }

    /**
     * Get a list of array except the given array of index.
     *
     * @param array $attributes List of array keys you want to exclude from your array.
     *
     * @see Arr::except()
     *
     * @return array
     */
    public function except(array $attributes)
    {
        return Arr::except($this->attributes, $attributes);
    }

    /**
     * Reset the attributes.
     *
     * @param array $default Default attributes after nuking current attributes.
     */
    public function nuke(array $default = array())
    {
        $this->attributes = $default;
    }

    /**
     * Get an attribute value.
     *
     * @param string $index
     * @param mixed  $default Default value if index doesn't exist
     *
     * @return mixed
     */
    public function get($index, $default = null)
    {
        return $this->has($index) ? $this->attributes[$index] : value($default);
    }

    /**
     * Get attributes value.
     *
     * @param mixed $attributes If it's null, it will return the whole attributes, if it's array, it will fetch only the given array value
     * @param mixed $default    Default value if attributes don't exist
     *
     * @return mixed
     */
    public function attributes($attributes = null, $default = null)
    {
        if (is_null($attributes)) {
            return $this->attributes;
        }

        if (is_array($attributes)) {
            $return = $this->only($attributes);

            return empty($return) ? value($default) : $return;
        }

        return value($default);
    }

    /**
     * Convert this implementation object to array.
     *
     * @param mixed $attributes Something you want to convert to array.
     *
     * @return array
     *
     * @see Arr::arToArray()
     */
    public function toArray($attributes = null)
    {
        if ($this->jsonAble === null) {
            $jsonAble = array();

            if ($attributes === null) {
                $attributes = $this->attributes;
            }

            foreach ($attributes as $key => $value) {
                if ($value instanceof CActiveRecord) {
                    $jsonAble[$key] = Arr::arToArray($value);
                } elseif ($value instanceof Arrayable) {
                    $jsonAble[$key] = $value->castToArray();
                } elseif (is_object($value)) {
                    $jsonAble[$key] = (array) $value;
                } elseif (is_array($value)) {
                    $jsonAble[$key] = $this->toArray($value);
                } else {
                    $jsonAble[$key] = $value;
                }
            }

            $this->jsonAble = $jsonAble;
        }

        return $this->jsonAble;
    }

    /**
     * {@inheritdoc}
     */
    public function castToArray()
    {
        return $this->toArray();
    }

    /**
     * Convert attributes to readable JSON.
     *
     * @param const $options JSON Decoding options.
     *
     * @return string
     */
    public function toJson($options = null)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Set an attribute value.
     *
     * @param string $index
     * @param mized  $value
     */
    public function offsetSet($index, $value)
    {
        $this->set($index, $value);
    }

    /**
     * Determine if index is exist in attributes.
     *
     * @param string $index
     *
     * @return bool
     */
    public function offsetExists($index)
    {
        return $this->has($index);
    }

    /**
     * Remove an attributes.
     *
     * @param string $index
     */
    public function offsetUnset($index)
    {
        $this->remove($index);
    }

    /**
     * Get an attribute value.
     *
     * @param string $index
     * @param mixed  $default Default value if index doesn't exist
     *
     * @return mixed
     */
    public function offsetGet($index)
    {
        return $this->get($index);
    }

    /**
     * JsonSerializable implementation.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Set an attribute value.
     *
     * @param string $index
     * @param mized  $value
     */
    public function __set($index, $value)
    {
        $this->set($index, $value);
    }

    /**
     * Get an attribute value.
     *
     * @param string $index
     * @param mixed  $default Default value if index doesn't exist
     *
     * @return mixed
     */
    public function __get($index)
    {
        return $this->get($index);
    }

    /**
     * Determine if index exists in attributes.
     *
     * @param string $index
     *
     * @return bool
     */
    public function __isset($index)
    {
        return $this->has($index);
    }

    /**
     * Remove an attributes.
     *
     * @param string $index
     */
    public function __unset($index)
    {
        $this->remove($index);
    }

    /**
     * Convert your collection to JSON.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }
}
