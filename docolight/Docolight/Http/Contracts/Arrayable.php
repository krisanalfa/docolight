<?php

namespace Docolight\Http\Contracts;

/**
 * Useful to strictly use an object that has implemented this contract in a response.
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
interface Arrayable
{
    /**
     * Convert implementation to an array.
     *
     * @return array
     */
    public function castToArray();

    /**
     * Bulk insert attributes.
     *
     * @param mixed $attributes
     */
    public function fill($attributes);
}
