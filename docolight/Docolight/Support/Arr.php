<?php

namespace Docolight\Support;

use CActiveRecord;
use Closure;
use Docolight\Support\Traits\Macroable;

/**
 * Array helper.
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
class Arr
{
    use Macroable;

    protected static $fetchedRelated = array();

    /**
     * Add an element to an array using "dot" notation if it doesn't exist.
     *
     * @param array  $array
     * @param string $key
     * @param mixed  $value
     *
     * @return array
     */
    public static function add($array, $key, $value)
    {
        if (is_null(static::get($array, $key))) {
            static::set($array, $key, $value);
        }

        return $array;
    }

    /**
     * Build a new array using a callback.
     *
     * @param array    $array
     * @param callable $callback
     *
     * @return array
     */
    public static function build($array, callable $callback)
    {
        $results = [];

        foreach ($array as $key => $value) {
            list($innerKey, $innerValue) = call_user_func($callback, $key, $value);

            $results[$innerKey] = $innerValue;
        }

        return $results;
    }

    /**
     * Collapse an array of arrays into a single array.
     *
     * @param array|\ArrayAccess $array
     *
     * @return array
     */
    public static function collapse($array)
    {
        $results = [];

        foreach ($array as $values) {
            if ($values instanceof Collection) {
                $values = $values->all();
            }

            $results = array_merge($results, $values);
        }

        return $results;
    }

    /**
     * Divide an array into two arrays. One with keys and the other with values.
     *
     * @param array $array
     *
     * @return array
     */
    public static function divide($array)
    {
        return [array_keys($array), array_values($array)];
    }

    /**
     * Flatten a multi-dimensional associative array with dots.
     *
     * @param array  $array
     * @param string $prepend
     *
     * @return array
     */
    public static function dot($array, $prepend = '')
    {
        $results = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $results = array_merge($results, static::dot($value, $prepend.$key.'.'));
            } else {
                $results[$prepend.$key] = $value;
            }
        }

        return $results;
    }

    /**
     * Get all of the given array except for a specified array of items.
     *
     * @param array        $array
     * @param array|string $keys
     *
     * @return array
     */
    public static function except($array, $keys)
    {
        static::forget($array, $keys);

        return $array;
    }

    /**
     * Return the first element in an array passing a given truth test.
     *
     * @param array    $array
     * @param callable $callback
     * @param mixed    $default
     *
     * @return mixed
     */
    public static function first(array $array, callable $callback = null, $default = null)
    {
        if ($callback === null) {
            return reset($array);
        }

        foreach ($array as $key => $value) {
            if (call_user_func($callback, $key, $value)) {
                return $value;
            }
        }

        return value($default);
    }

    /**
     * Return the last element in an array passing a given truth test.
     *
     * @param array    $array
     * @param callable $callback
     * @param mixed    $default
     *
     * @return mixed
     */
    public static function last(array $array, callable $callback = null, $default = null)
    {
        if ($callback === null) {
            return end($array);
        }

        return static::first(array_reverse($array), $callback, $default);
    }

    /**
     * Flatten a multi-dimensional array into a single level.
     *
     * @param array $array
     *
     * @return array
     */
    public static function flatten($array)
    {
        $return = [];

        array_walk_recursive($array, function ($x) use (&$return) { $return[] = $x; });

        return $return;
    }

    /**
     * Remove one or many array items from a given array using "dot" notation.
     *
     * @param array        $array
     * @param array|string $keys
     */
    public static function forget(&$array, $keys)
    {
        $original = &$array;

        foreach ((array) $keys as $key) {
            $parts = explode('.', $key);

            while (count($parts) > 1) {
                $part = array_shift($parts);

                if (isset($array[$part]) and is_array($array[$part])) {
                    $array = &$array[$part];
                }
            }

            unset($array[array_shift($parts)]);

            // clean up after each pass
            $array = &$original;
        }
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param array  $array
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public static function get($array, $key, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }

        if (isset($array[$key])) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) or !array_key_exists($segment, $array)) {
                return value($default);
            }

            $array = $array[$segment];
        }

        return $array;
    }

    /**
     * Check if an item exists in an array using "dot" notation.
     *
     * @param array  $array
     * @param string $key
     *
     * @return bool
     */
    public static function has($array, $key)
    {
        if (empty($array) or is_null($key)) {
            return false;
        }

        if (array_key_exists($key, $array)) {
            return true;
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) or !array_key_exists($segment, $array)) {
                return false;
            }

            $array = $array[$segment];
        }

        return true;
    }

    /**
     * Get a subset of the items from the given array.
     *
     * @param array        $array
     * @param array|string $keys
     *
     * @return array
     */
    public static function only($array, $keys)
    {
        return array_intersect_key($array, array_flip((array) $keys));
    }

    /**
     * Pluck an array of values from an array.
     *
     * @param array             $array
     * @param string|array      $value
     * @param string|array|null $key
     *
     * @return array
     */
    public static function pluck($array, $value, $key = null)
    {
        $results = [];

        list($value, $key) = static::explodePluckParameters($value, $key);

        foreach ($array as $item) {
            $itemValue = data_get($item, $value);

            // If the key is "null", we will just append the value to the array and keep
            // looping. Otherwise we will key the array using the value of the key we
            // received from the developer. Then we'll return the final array form.
            if (is_null($key)) {
                $results[] = $itemValue;
            } else {
                $itemKey = data_get($item, $key);

                $results[$itemKey] = $itemValue;
            }
        }

        return $results;
    }

    /**
     * Explode the "value" and "key" arguments passed to "pluck".
     *
     * @param string|array      $value
     * @param string|array|null $key
     *
     * @return array
     */
    protected static function explodePluckParameters($value, $key)
    {
        $value = is_array($value) ? $value : explode('.', $value);

        $key = is_null($key) or is_array($key) ? $key : explode('.', $key);

        return [$value, $key];
    }

    /**
     * Get a value from the array, and remove it.
     *
     * @param array  $array
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public static function pull(&$array, $key, $default = null)
    {
        $value = static::get($array, $key, $default);

        static::forget($array, $key);

        return $value;
    }

    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param array  $array
     * @param string $key
     * @param mixed  $value
     *
     * @return array
     */
    public static function set(&$array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }

        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (!isset($array[$key]) or !is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;

        return $array;
    }

    /**
     * Sort the array using the given callback.
     *
     * @param array    $array
     * @param callable $callback
     *
     * @return array
     */
    public static function sort($array, callable $callback)
    {
        return Collection::make($array)->sortBy($callback)->all();
    }

    /**
     * Filter the array using the given callback.
     *
     * @param array    $array
     * @param callable $callback
     *
     * @return array
     */
    public static function where($array, callable $callback)
    {
        $filtered = [];

        foreach ($array as $key => $value) {
            if (call_user_func($callback, $key, $value)) {
                $filtered[$key] = $value;
            }
        }

        return $filtered;
    }

    /**
     * Convert `CActiveRecord` implemetation to array, including all of it's relation.
     *
     * @param CActiveRecord $model        Your model implementation.
     * @param bool          $withRelation Choose whether to fetch with your relation too or not.
     * @param bool          $store        Store relation name in a temporary container. Useful to prefent infinite loop.
     *
     * @return array
     *
     * @link http://www.yiiframework.com/doc/api/1.1/CActiveRecord CActiveRecord is the base class for classes representing relational data.
     */
    public static function arToArray(CActiveRecord $model, $withRelation = true, $store = true)
    {
        $return = $model->getAttributes();

        if ($withRelation) {
            if ($store) {
                static::$fetchedRelated[] = lcfirst(get_class($model));
            }

            foreach ($model->relations() as $relationName => $relationConfiguration) {
                if (! in_array($relationName, static::$fetchedRelated)) {
                    if ($store) {
                        static::$fetchedRelated[] = $relationName;
                    }

                    $relation = $model->getRelated($relationName);

                    if ($relation instanceof CActiveRecord) {
                        $return[$relationName] = static::arToArray($relation, true);
                    } elseif (is_array($relation) and ! empty($relation)) {
                        $return[$relationName] = array();

                        foreach ($relation as $key => $relationModel) {
                            if ($relationModel instanceof CActiveRecord) {
                                $return[$relationName][] = static::arToArray($relationModel, true, false);
                            }
                        }
                    }
                }
            }
        }

        return $return;
    }

    /**
     * Flip your array, mostly comes from stackable form like this:.
     *
     * ```php
     * <input name="first-name[]" />
     * <input name="last-name[]" />
     *
     * // It will produce $_POST array like this:
     *
     * [
     *     "first-name" => ["Ganesha", "Krisan", "Farid"],
     *     "last-name"  => ["Muharso", "Timur", "Hidayat"] ]
     *
     * // This method will convert the array into this form:
     *
     * [
     *
     *     [
     *         "first-name" => "Ganesha",
     *         "last-name" => "Muharso" ],
     *
     *     [
     *         "first-name" => "Krisan",
     *         "last-name" => "Timur" ],
     *
     *     [
     *         "first-name" => "Farid",
     *         "last-name" => "Hidayat" ]
     * ]
     *
     * // So you can loop them, and save them to your model via:
     *
     * foreach(Arr::group($personInput) as $person) {
     *     $model = new User;
     *
     *     $model->set($person)->save();
     * }
     * ```
     *
     * @param array $array
     *
     * @return array
     */
    public static function group(array $array)
    {
        $data = array();

        for ($i = 0; $i < count(reset($array)); $i++) {
            $data[$i] = array();

            foreach ($array as $key => $value) {
                $data[$i][$key] = $value[$i];
            }
        }

        return $data;
    }

    /**
     * Group array based on return from closure.
     *
     * @param array   $array
     * @param Closure $callback
     *
     * @return array
     */
    public static function groupBy(array $array, Closure $callback)
    {
        $return = array();

        foreach ($array as $key => $item) {
            $return[call_user_func($callback, $key, $item)][] = $item;
        }

        ksort($return, SORT_NUMERIC);

        return $return;
    }

    /**
     * Get get array identified by a regex for it's index name.
     *
     * @param string $pattern
     * @param array  $input
     * @param int    $flags
     *
     * @return array
     */
    public static function pregOnly($pattern, array $input, $flags = 0)
    {
        return array_intersect_key($input, array_flip(preg_grep($pattern, array_keys($input), $flags)));
    }

    /**
     * Replace your array keys.
     *
     * ```php
     * $array = [
     *     ':type_address'     => 'Foo',
     *     ':type_citizenship' => 'Bar',
     *     ':type_city'        => 'Baz',
     *     ':type_country'     => 'Qux' ]
     *
     * Arr::replaceKey($array, ':type', 'user')
     *
     * // Will produce
     *
     * $array = [
     *     'user_address'     => 'Foo',
     *     'user_citizenship' => 'Bar',
     *     'user_city'        => 'Baz',
     *     'user_country'     => 'Qux' ]
     * ```
     *
     * @param array           $input
     * @param string|callable $search
     * @param string          $replacement
     *
     * @return array
     */
    public static function replaceKey(array $input, $search, $replacement = '')
    {
        $array = array();

        foreach ($input as $key => $value) {
            if (is_callable($search)) {
                $array[$search($key)] = $value;
            } else {
                $array[preg_replace($search, $replacement, $key)] = $value;
            }
        }

        return $array;
    }

    /**
     * Replace your array value.
     *
     * ```php
     * $header = [
     *     ':type_address',
     *     ':type_citizenship',
     *     ':type_city',
     *     ':type_country' ]
     *
     * Arr::replaceValue($header, ':type_')
     *
     * // Will produce:
     *
     * $header = [
     *     'address',
     *     'citizenship',
     *     'city',
     *     'country' ]
     * ```
     *
     * @param array           $input
     * @param string|callable $search
     * @param string          $replacement
     *
     * @return array
     */
    public static function replaceValue(array $input, $search, $replacement = '')
    {
        $array = array();

        foreach ($input as $key => $value) {
            if (is_callable($search)) {
                $array[$key] = $search($value);
            } else {
                $array[$key] = str_replace($search, $replacement, $value);
            }
        }

        return $array;
    }

    /**
     * Determine your multidimension array depth.
     *
     * @param array $array
     *
     * @return int
     */
    public static function depth(array $array)
    {
        $maxDepth = 1;

        foreach ($array as $value) {
            if (is_array($value)) {
                $depth = static::depth($value) + 1;

                if ($depth > $maxDepth) {
                    $maxDepth = $depth;
                }
            }
        }

        return $maxDepth;
    }

    /**
     * Determine if array is empty, works on multidimension array.
     *
     * @param array $array Array you want to check whether it's empty or not
     *
     * @return bool
     */
    public static function isEmpty(array $array)
    {
        if (static::depth($array) > 0) {
            $empty = true;

            foreach ($array as $value) {
                $empty = (empty($value) or is_null($value));
            }

            return $empty;
        }

        return empty($array);
    }
}
