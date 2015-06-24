<?php

namespace Docolight\Http;

use Docolight\Support\Set;

/**
 * HTTP Headers.
 *
 * ```php
 * $headers = new Docolight\Http\Headers;
 *
 * $headers->set('My-Header-Index', 'My header value');
 * ```
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
class Headers extends Set
{
    /**
     * Special-case HTTP headers that are otherwise unidentifiable as HTTP headers.
     * Typically, HTTP headers in the $_SERVER array will be prefixed with
     * `HTTP_` or `X_`. These are not so we list them here for later reference.
     *
     * @var array
     */
    protected static $special = array(
        'CONTENT_TYPE',
        'CONTENT_LENGTH',
        'PHP_AUTH_USER',
        'PHP_AUTH_PW',
        'PHP_AUTH_DIGEST',
        'AUTH_TYPE',
    );

    /**
     * Extract HTTP headers from an array of data (e.g. $_SERVER).
     *
     * @param array $data
     *
     * @return array
     */
    public static function extract($data)
    {
        $results = array();

        foreach ($data as $key => $value) {
            $key = strtoupper($key);

            if (strpos($key, 'X_') === 0 or strpos($key, 'HTTP_') === 0 or in_array($key, static::$special)) {
                if ($key !== 'HTTP_CONTENT_LENGTH') {
                    $results[$key] = $value;
                }
            }
        }

        return $results;
    }

    /**
     * Transform header name into canonical form.
     *
     * @param string $key
     *
     * @return string
     */
    protected function normalizeKey($key)
    {
        $key = strtolower($key);
        $key = str_replace(array('-', '_'), ' ', $key);
        $key = preg_replace('#^http #', '', $key);
        $key = ucwords($key);
        $key = str_replace(' ', '-', $key);

        return $key;
    }
}
