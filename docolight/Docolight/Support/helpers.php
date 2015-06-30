<?php

use Docolight\Support\Arr;
use Docolight\Support\Str;
use Docolight\Support\Fluent;
use Docolight\Support\Collection;
use Docolight\Support\Debug\Dumper;

if (!function_exists('array_add')) {
    /**
     * Add an element to an array using "dot" notation if it doesn't exist.
     *
     * @param array  $array
     * @param string $key
     * @param mixed  $value
     *
     * @return array
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function array_add($array, $key, $value)
    {
        return Arr::add($array, $key, $value);
    }
}

if (!function_exists('array_build')) {
    /**
     * Build a new array using a callback.
     *
     * @param array    $array
     * @param callable $callback
     *
     * @return array
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function array_build($array, callable $callback)
    {
        return Arr::build($array, $callback);
    }
}

if (!function_exists('array_collapse')) {
    /**
     * Collapse an array of arrays into a single array.
     *
     * @param array|\ArrayAccess $array
     *
     * @return array
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function array_collapse($array)
    {
        return Arr::collapse($array);
    }
}

if (!function_exists('array_divide')) {
    /**
     * Divide an array into two arrays. One with keys and the other with values.
     *
     * @param array $array
     *
     * @return array
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function array_divide($array)
    {
        return Arr::divide($array);
    }
}

if (!function_exists('array_dot')) {
    /**
     * Flatten a multi-dimensional associative array with dots.
     *
     * @param array  $array
     * @param string $prepend
     *
     * @return array
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function array_dot($array, $prepend = '')
    {
        return Arr::dot($array, $prepend);
    }
}

if (!function_exists('array_except')) {
    /**
     * Get all of the given array except for a specified array of items.
     *
     * @param array        $array
     * @param array|string $keys
     *
     * @return array
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function array_except($array, $keys)
    {
        return Arr::except($array, $keys);
    }
}

if (!function_exists('array_first')) {
    /**
     * Return the first element in an array passing a given truth test.
     *
     * @param array         $array
     * @param callable|null $callback
     * @param mixed         $default
     *
     * @return mixed
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function array_first($array, callable $callback = null, $default = null)
    {
        return Arr::first($array, $callback, $default);
    }
}

if (!function_exists('array_last')) {
    /**
     * Return the last element in an array passing a given truth test.
     *
     * @param array         $array
     * @param callable|null $callback
     * @param mixed         $default
     *
     * @return mixed
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function array_last($array, $callback = null, $default = null)
    {
        return Arr::last($array, $callback, $default);
    }
}

if (!function_exists('array_flatten')) {
    /**
     * Flatten a multi-dimensional array into a single level.
     *
     * @param array $array
     *
     * @return array
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function array_flatten($array)
    {
        return Arr::flatten($array);
    }
}

if (!function_exists('array_forget')) {
    /**
     * Remove one or many array items from a given array using "dot" notation.
     *
     * @param array        $array
     * @param array|string $keys
     */
    function array_forget(&$array, $keys)
    {
        return Arr::forget($array, $keys);
    }
}

if (!function_exists('array_get')) {
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param array  $array
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function array_get($array, $key, $default = null)
    {
        return Arr::get($array, $key, $default);
    }
}

if (!function_exists('array_has')) {
    /**
     * Check if an item exists in an array using "dot" notation.
     *
     * @param array  $array
     * @param string $key
     *
     * @return bool
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function array_has($array, $key)
    {
        return Arr::has($array, $key);
    }
}

if (!function_exists('array_only')) {
    /**
     * Get a subset of the items from the given array.
     *
     * @param array        $array
     * @param array|string $keys
     *
     * @return array
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function array_only($array, $keys)
    {
        return Arr::only($array, $keys);
    }
}

if (!function_exists('array_pluck')) {
    /**
     * Pluck an array of values from an array.
     *
     * @param array  $array
     * @param string $value
     * @param string $key
     *
     * @return array
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function array_pluck($array, $value, $key = null)
    {
        return Arr::pluck($array, $value, $key);
    }
}

if (!function_exists('array_pull')) {
    /**
     * Get a value from the array, and remove it.
     *
     * @param array  $array
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function array_pull(&$array, $key, $default = null)
    {
        return Arr::pull($array, $key, $default);
    }
}

if (!function_exists('array_set')) {
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
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function array_set(&$array, $key, $value)
    {
        return Arr::set($array, $key, $value);
    }
}

if (!function_exists('array_sort')) {
    /**
     * Sort the array using the given callback.
     *
     * @param array    $array
     * @param callable $callback
     *
     * @return array
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function array_sort($array, callable $callback)
    {
        return Arr::sort($array, $callback);
    }
}

if (!function_exists('array_where')) {
    /**
     * Filter the array using the given callback.
     *
     * @param array    $array
     * @param callable $callback
     *
     * @return array
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function array_where($array, callable $callback)
    {
        return Arr::where($array, $callback);
    }
}

if (!function_exists('camel_case')) {
    /**
     * Convert a value to camel case.
     *
     * @param string $value
     *
     * @return string
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function camel_case($value)
    {
        return Str::camel($value);
    }
}

if (!function_exists('class_basename')) {
    /**
     * Get the class "basename" of the given object / class.
     *
     * @param string|object $class
     *
     * @return string
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function class_basename($class)
    {
        $class = is_object($class) ? get_class($class) : $class;

        return basename(str_replace('\\', '/', $class));
    }
}

if (!function_exists('class_uses_recursive')) {
    /**
     * Returns all traits used by a class, its subclasses and trait of their traits.
     *
     * @param string $class
     *
     * @return array
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function class_uses_recursive($class)
    {
        $results = array();

        foreach (array_merge(array($class => $class), class_parents($class)) as $class) {
            $results += trait_uses_recursive($class);
        }

        return array_unique($results);
    }
}

if (!function_exists('collect')) {
    /**
     * Create a collection from the given value.
     *
     * @param mixed $value
     *
     * @return \Docolight\Support\Collection
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function collect($value = null)
    {
        return new Collection($value);
    }
}

if (!function_exists('fluent')) {
    /**
     * Create a new model from the given value.
     *
     * @param mixed $value
     *
     * @return \Docolight\Support\Fluent
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function fluent($value = null)
    {
        return new Fluent($value);
    }
}

if (!function_exists('data_get')) {
    /**
     * Get an item from an array or object using "dot" notation.
     *
     * @param mixed        $target
     * @param string|array $key
     * @param mixed        $default
     *
     * @return mixed
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function data_get($target, $key, $default = null)
    {
        if (is_null($key)) {
            return $target;
        }

        $key = is_array($key) ? $key : explode('.', $key);

        foreach ($key as $segment) {
            if (is_array($target)) {
                if (!array_key_exists($segment, $target)) {
                    return value($default);
                }

                $target = $target[$segment];
            } elseif ($target instanceof ArrayAccess) {
                if (!isset($target[$segment])) {
                    return value($default);
                }

                $target = $target[$segment];
            } elseif (is_object($target)) {
                if (!isset($target->{$segment})) {
                    return value($default);
                }

                $target = $target->{$segment};
            } else {
                return value($default);
            }
        }

        return $target;
    }
}

if (!function_exists('e')) {
    /**
     * Escape HTML entities in a string.
     *
     * @param string $value
     *
     * @return string
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function e($value)
    {
        return htmlentities($value, ENT_QUOTES, 'UTF-8', false);
    }
}

if (!function_exists('ends_with')) {
    /**
     * Determine if a given string ends with a given substring.
     *
     * @param string       $haystack
     * @param string|array $needles
     *
     * @return bool
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function ends_with($haystack, $needles)
    {
        return Str::endsWith($haystack, $needles);
    }
}

if (!function_exists('head')) {
    /**
     * Get the first element of an array. Useful for method chaining.
     *
     * @param array $array
     *
     * @return mixed
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function head($array)
    {
        return reset($array);
    }
}

if (!function_exists('last')) {
    /**
     * Get the last element from an array.
     *
     * @param array $array
     *
     * @return mixed
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function last($array)
    {
        return end($array);
    }
}

if (!function_exists('object_get')) {
    /**
     * Get an item from an object using "dot" notation.
     *
     * @param object $object
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function object_get($object, $key, $default = null)
    {
        if (is_null($key) || trim($key) == '') {
            return $object;
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_object($object) || !isset($object->{$segment})) {
                return value($default);
            }

            $object = $object->{$segment};
        }

        return $object;
    }
}

if (!function_exists('preg_replace_sub')) {
    /**
     * Replace a given pattern with each value in the array in sequentially.
     *
     * @param string $pattern
     * @param array  $replacements
     * @param string $subject
     *
     * @return string
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function preg_replace_sub($pattern, &$replacements, $subject)
    {
        return preg_replace_callback($pattern, function ($match) use (&$replacements) {
            foreach ($replacements as $key => $value) {
                return array_shift($replacements);
            }

        }, $subject);
    }
}

if (!function_exists('array_sort_recursive')) {
    /**
     * Recursively sort an array by keys and values.
     *
     * @param array $array
     *
     * @return array
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function array_sort_recursive($array)
    {
        foreach ($array as &$value) {
            if (is_array($value) && isset($value[0])) {
                sort($value);
            } elseif (is_array($value)) {
                array_sort_recursive($value);
            }
        }

        ksort($array);

        return $array;
    }
}

if (!function_exists('snake_case')) {
    /**
     * Convert a string to snake case.
     *
     * @param string $value
     * @param string $delimiter
     *
     * @return string
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function snake_case($value, $delimiter = '_')
    {
        return Str::snake($value, $delimiter);
    }
}

if (!function_exists('starts_with')) {
    /**
     * Determine if a given string starts with a given substring.
     *
     * @param string       $haystack
     * @param string|array $needles
     *
     * @return bool
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function starts_with($haystack, $needles)
    {
        return Str::startsWith($haystack, $needles);
    }
}

if (!function_exists('str_contains')) {
    /**
     * Determine if a given string contains a given substring.
     *
     * @param string       $haystack
     * @param string|array $needles
     *
     * @return bool
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function str_contains($haystack, $needles)
    {
        return Str::contains($haystack, $needles);
    }
}

if (!function_exists('str_finish')) {
    /**
     * Cap a string with a single instance of a given value.
     *
     * @param string $value
     * @param string $cap
     *
     * @return string
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function str_finish($value, $cap)
    {
        return Str::finish($value, $cap);
    }
}

if (!function_exists('str_is')) {
    /**
     * Determine if a given string matches a given pattern.
     *
     * @param string $pattern
     * @param string $value
     *
     * @return bool
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function str_is($pattern, $value)
    {
        return Str::is($pattern, $value);
    }
}

if (!function_exists('str_limit')) {
    /**
     * Limit the number of characters in a string.
     *
     * @param string $value
     * @param int    $limit
     * @param string $end
     *
     * @return string
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function str_limit($value, $limit = 100, $end = '...')
    {
        return Str::limit($value, $limit, $end);
    }
}

if (!function_exists('str_random')) {
    /**
     * Generate a more truly "random" alpha-numeric string.
     *
     * @param int $length
     *
     * @return string
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     *
     * @throws \RuntimeException
     */
    function str_random($length = 16)
    {
        return Str::random($length);
    }
}

if (!function_exists('str_replace_array')) {
    /**
     * Replace a given value in the string sequentially with an array.
     *
     * @param string $search
     * @param array  $replace
     * @param string $subject
     *
     * @return string
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function str_replace_array($search, array $replace, $subject)
    {
        foreach ($replace as $value) {
            $subject = preg_replace('/'.$search.'/', $value, $subject, 1);
        }

        return $subject;
    }
}

if (!function_exists('str_slug')) {
    /**
     * Generate a URL friendly "slug" from a given string.
     *
     * @param string $title
     * @param string $separator
     *
     * @return string
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function str_slug($title, $separator = '-')
    {
        return Str::slug($title, $separator);
    }
}

if (!function_exists('studly_case')) {
    /**
     * Convert a value to studly caps case.
     *
     * @param string $value
     *
     * @return string
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function studly_case($value)
    {
        return Str::studly($value);
    }
}

if (!function_exists('title_case')) {
    /**
     * Convert a value to title case.
     *
     * @param string $value
     *
     * @return string
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function title_case($value)
    {
        return Str::title($value);
    }
}

if (!function_exists('trimtolower')) {
    /**
     * Trim and convert a value to lowercase.
     *
     * @param string $value
     *
     * @return string
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function trimtolower($value)
    {
        return trim(mb_strtolower($value));
    }
}

if (!function_exists('trait_uses_recursive')) {
    /**
     * Returns all traits used by a trait and its traits.
     *
     * @param string $trait
     *
     * @return array
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function trait_uses_recursive($trait)
    {
        $traits = class_uses($trait);

        foreach ($traits as $trait) {
            $traits += trait_uses_recursive($trait);
        }

        return $traits;
    }
}

if (!function_exists('value')) {
    /**
     * Return the default value of the given value.
     *
     * @param mixed $value
     *
     * @return mixed
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

if (!function_exists('with')) {
    /**
     * Return the same value you passed to it's argument. Very usefull to access chain object / method in non-reuse-instance cases (PHP v5.3), like `with(with($foo = new Foo)->foo())->bar($foo)`.
     *
     * @param mixed $anything
     *
     * @return mixed
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function with($anything)
    {
        return $anything;
    }
}

if (!function_exists('def')) {
    /**
     * Save way to access array or public property from an object.
     *
     * @param array|object $stack
     * @param string       $offset
     * @param mixed        $default
     *
     * @return mixed
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function def($stack, $offset, $default = null)
    {
        if (is_array($stack)) {
            if (array_key_exists($offset, $stack)) {
                return $stack[$offset];
            }
        } elseif (is_object($stack)) {
            if (property_exists($stack, $offset) or $stack->__isset($offset)) {
                return $stack->{$offset};
            } else {
                return def((array) $stack, $offset, value($default));
            }
        } else {
            throw new InvalidArgumentException('The first argument of def must be an array or object.');
        }

        return value($default);
    }
}

if (!function_exists('container')) {
    /**
     * Get the available container instance. Make sure you have enabled `Container` components. In your `components` configuration, add this lines:.
     *
     * ```php
     * 'container' => array( 'class' => 'Container' ),
     * ```
     *
     * @param string $make
     * @param array  $parameters
     *
     * @return mixed|\Container
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function container($make = null, array $parameters = array())
    {
        if (func_num_args() === 0) {
            return Yii::app()->getComponent('container', false);
        }

        return Yii::app()->getComponent('container', false)->make($make, $parameters);
    }
}

if (!function_exists('dump')) {
    /**
     * The Dumper component provides mechanisms for walking through any arbitrary PHP variable. Built on top, it provides a better `dump()` function that you can use instead of `var_dump`.
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function dump()
    {
        array_map(function ($var) {
            value(new Dumper())->dump($var);
        }, func_get_args());
    }
}

if (!function_exists('dd')) {
    /**
     * Same as `dump`, but after dumping, we also stop the PHP exectution runtime by calling `die()` function.
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function dd()
    {
        call_user_func_array('dump', func_get_args());

        die(1);
    }
}

if (!function_exists('session')) {
    /**
     * Session helper.
     *
     * @param string|null $identifier If you don't pass anything in first argument, it will return the session object itself.
     * @param mixed|null  $value      If you pass a null to second argument, it will destroy the session on it's identifier passed in first argument.
     *
     * @return mixed
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function session($identifier = null, $value = null)
    {
        $session = Yii::app()->session;

        if (! $session instanceof CHttpSession) {
            throw new Exception("Session has not been configured yet.");
        }

        $numberOfArguments = func_num_args();

        // If you don't pass any argument of the identifier is null, I'll return the session manager object.
        if ($numberOfArguments === 0 or $identifier === null) {
            return $session;
        }
        // If the first argument is not null, I'll return the session value on it's identifier
        elseif ($numberOfArguments === 1 and $identifier !== null) {
            return $session->get($identifier, value($value));
        }
        // If the second argument is not null and the identifier is not null and value is not null
        // I'll set new value of session
        elseif ($numberOfArguments === 2 and $identifier !== null and $value !== null) {
            $session[$identifier] = $value;
        }
        // If the second argument is null and the identifier is not null but value is null
        // I'll destroy the session on it's identifier
        elseif ($numberOfArguments === 2 and $identifier !== null and $value === null) {
            $session->remove($identifier);
        }
    }
}

if (!function_exists('cache')) {
    /**
     * A cache helper to simplify it's usage.
     *
     * @param string $identifier Cache identifier.
     * @param mixed  $value      Cache value.
     * @param int    $time       Cache timeout.
     * @param string $dependency Class name dependency.
     * @param array  $params     Dependency parameters.
     *
     * @return mixed
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function cache($identifier = null, $value = null, $time = null, $dependency = null, array $params = array())
    {
        $cache = Yii::app()->cache;

        // Strict using CCache abstraction class, if not, just throw a catchable exception.
        if (!$cache instanceof CCache) {
            throw new RuntimeException('Cache not configured.');
        }

        $numberOfArguments = func_num_args();

        // If you don't pass any arguments to this function,
        // or the $identifier is null. then it will return CCache implementation
        if ($numberOfArguments === 0 or $identifier === null) {
            return $cache;
        }
        // If you pass only one arguments and it's not null, then it will get
        // the cache value based on the value of $identifier
        elseif ($numberOfArguments === 1 and $identifier !== null) {
            return $cache->get($identifier);
        }
        // If you pass only two arguments, the identifier is not null and the value is null
        // It will asume you want to delete the cache
        elseif ($numberOfArguments === 2 and $identifier !== null and $value === null) {
            return $cache->delete($identifier);
        }
        // Anyhow, if you pass more than one arguments to this method, then it will assume you
        // want to store a cache.
        else {
            return $cache->set(
                $identifier,
                $value,
                $time,
                (is_string($dependency) ? container()->make($dependency, $params)
                                        : (($dependency instanceof CCacheDependency) ? $dependency
                                                                                     : null)
                )
            );
        }
    }
}

if (!function_exists('request')) {
    /**
     * Get post / get value
     *
     * @param null|string $identifier
     * @param mixed       $default
     *
     * @return mixed
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function request($identifier = null, $default = null)
    {
        $request = Yii::app()->request;

        if (! $request instanceof CHttpRequest) {
            throw new Exception("Request component has not been cofigured yet.");
        }

        if (func_num_args() === 0 or $identifier === null) {
            return $request;
        }

        return $request->getParam($identifier, value($default));
    }
}

if (!function_exists('input')) {
    /**
     * Get $_POST value
     *
     * @param null|string $identifier
     * @param mixed       $default
     *
     * @return mixed
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function input($identifier = null, $default = null)
    {
        if (func_num_args() === 0 or $identifier === null) {
            return $_POST;
        }

        return request()->getPost($identifier, value($default));
    }
}

if (!function_exists('array_replace_value')) {
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
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function array_replace_value(array $input, $search, $replacement = '')
    {
        return Arr::replaceValue($input, $search, $replacement);
    }
}

if (!function_exists('get')) {
    /**
     * Get $_GET value
     *
     * @param null|string $identifier
     * @param mixed       $default
     *
     * @return mixed
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function get($identifier = null, $default = null)
    {
        if (func_num_args() === 0 or $identifier === null) {
            return $_GET;
        }

        return request()->getQuery($identifier, value($default));
    }
}

if (!function_exists('transaction')) {
    /**
     * Get a transaction on a connection.
     *
     * @param \CDbConnection $connection
     *
     * @return \CDbTransaction
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function transaction(CDbConnection $connection)
    {
        $transaction = $connection->getCurrentTransaction();

        if (is_null($transaction)) {
            $transaction = $connection->beginTransaction();
        }

        if (! $connection->getActive()) {
            $connection->setActive(true);
        }

        return $transaction;
    }
}

if (!function_exists('response')) {
    /**
     * Make response even quicker.
     *
     * @param string  $driver          Possible values are 'base' | 'json' | null
     * @param integer $status          Your HTTP status code
     * @param mixed   $body            Your response body
     * @param array   $headers         Index - Value paired array for your header information
     * @param bool    $immediatelySend Choose whether to immediately send the response or not
     *
     * @return mixed
     *
     * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
     */
    function response($driver = 'base', $status = 200, $body = '', array $headers = array(), $immediatelySend = false)
    {
        if (! container()->bound('Docolight\Http\ResponseFactory')) {
            throw new RuntimeException('Class [Docolight\Http\ResponseFactory] has not been bound yet.');
        }

        $numberOfArguments = func_num_args();

        $response = container('response');

        if ($numberOfArguments === 0) {
            return $response;
        } elseif ($numberOfArguments === 1) {
            return $response->produce(((!$driver) ? 'base' : $driver));
        }

        $response = $response->produce($driver);

        $response->setStatus($status);

        $response->setBody($body);

        array_walk($headers, function ($value, $key) use ($response) {
            $response->headers->set($key, $value);
        });

        return ($immediatelySend) ? $response->send()
                                  : $response;
    }
}
